<?php

namespace App\Livewire\Order;

use App\Models\Car;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class OrderForm extends Component
{
    public Car $car;
    public $name;
    public $phone;
    public $address;
    public $mapReady = true;
    public $payment_method;
    public $selected_bank = null;
    public $selected_card_type = null; 
    public $latitude = null;
    public $longitude = null;
    public $showBankOptions = false;
    public $showCardOptions = false; 
    public $available_banks = [];
    public $available_cards = []; 

    protected $rules = [
        'name' => 'required|min:3',
        'phone' => 'required|numeric',
        'address' => 'required|min:10',
        'payment_method' => 'required|in:cash,transfer_bank,credit_card',
        'selected_bank' => 'required_if:payment_method,transfer_bank',
        'selected_card_type' => 'required_if:payment_method,credit_card', 
        'latitude' => 'required',
        'longitude' => 'required',
    ];

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi',
        'name.min' => 'Nama lengkap minimal 3 karakter',
        'phone.required' => 'Nomor telepon wajib diisi',
        'phone.numeric' => 'Nomor telepon harus berupa angka',
        'address.required' => 'Alamat wajib diisi',
        'address.min' => 'Alamat minimal 10 karakter',
        'payment_method.required' => 'Metode pembayaran wajib dipilih',
        'payment_method.in' => 'Metode pembayaran tidak valid',
        'latitude.required' => 'Silakan tentukan lokasi di peta',
        'longitude.required' => 'Silakan tentukan lokasi di peta',
        'selected_bank.required_if' => 'Silakan pilih bank untuk transfer',
        'selected_card_type.required_if' => 'Silakan pilih jenis kartu kredit',
    ];

    public function mount(Car $car)
    {
        $this->car = $car;
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone_number;
        $this->address = $user->address;

        // Set lokasi default jika user sudah pernah menyimpan koordinat
        if ($user->latitude && $user->longitude) {
            $this->latitude = $user->latitude;
            $this->longitude = $user->longitude;
        }

        // inisialisasi status peta
        $this->mapReady = true;

        // Inisialisasi bank yang tersedia untuk midtrans
        $this->available_banks = [
            'bank_transfer_bca' => 'BCA',
            'bank_transfer_bni' => 'BNI',
            'bank_transfer_bri' => 'BRI',
            'bank_transfer_mandiri' => 'Mandiri',
            'bank_transfer_permata' => 'Permata'
        ];
        
        // Inisialisasi kartu kredit yang tersedia untuk midtrans
        $this->available_cards = [
            'visa' => 'Visa',
            'mastercard' => 'Mastercard',
            'jcb' => 'JCB',
            'amex' => 'American Express'
        ];
    }

    public function updatedPaymentMethod($value)
    {
        $this->showBankOptions = ($value === 'transfer_bank');
        $this->showCardOptions = ($value === 'credit_card');
        
        // Reset bank yang dipilih jika payment method bukan merupakan transfer bank
        if ($value !== 'transfer_bank') {
            $this->selected_bank = null;
        }
        
        // Reset bank yang dipilih jika payment method bukan merupakan kartu kredit
        if ($value !== 'credit_card') {
            $this->selected_card_type = null;
        }
        
        // Pastikan peta ditampilkan setelah melakukan update
        $this->dispatch('preserveMap');
    }

    public function updateLocation($lat, $lng, $addressText = null)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        if ($addressText && trim($addressText) !== '') {
            $this->address = $addressText;

            // Dispatch event untuk animasi visual feedback
            $this->dispatch('locationUpdated');
        }

        $this->dispatch('mapUpdateCoordinates', [
            'lat' => $lat,
            'lng' => $lng
        ]);
    }

    public function updateAddress($address)
    {
        if (!empty($address)) {
            $this->address = $address;
            \Log::info("Alamat diperbarui ke: $address");
        }
    }

    public function submitOrder()
    {
        $this->validate();

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'car_id' => $this->car->id,
            'transaction_date' => now()->format('d-m-Y H:i:s'),
            'total_amount' => $this->car->price,
            'payment_method' => $this->payment_method,
            'selected_bank' => $this->selected_bank,
            'status' => 'pending',
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'order_address' => $this->address,
            'order_id' => 'ORDER-' . time() . '-' . Auth::id(),
        ]);

        if (in_array($this->payment_method, ['transfer_bank', 'credit_card'])) {
            $this->processMidtransPayment($transaction);
        } else {
            // Dispatch event untuk redirect ke halaman transaksi
            $this->dispatch('orderCreated', $transaction->id);
        }
    }

    protected function processMidtransPayment($transaction)
    {
        $serverKey = config('midtrans.server_key');
        
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->order_id,
                'gross_amount' => (int)$transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $this->name,
                'phone' => $this->phone,
                'billing_address' => [
                    'address' => $this->address,
                ],
            ],
            'item_details' => [
                [
                    'id' => $this->car->id,
                    'price' => (int)$this->car->price,
                    'quantity' => 1,
                    'name' => $this->car->brand . ' ' . $this->car->model . ' (' . $this->car->year . ')',
                ]
            ],
        ];

        // Konfigurasi payment method
        if ($this->payment_method === 'transfer_bank') {
            // Untuk transfer bank, tentukan bank dan tipe pembayaran
            $params['enabled_payments'] = [$this->selected_bank];
            
            // Ekstrak kode bank dari selected_bank value (contoh, 'bank_transfer_bca' -> 'bca')
            $bankCode = strtoupper(str_replace('bank_transfer_', '', $this->selected_bank));
            
            $params['bank_transfer'] = [
                'bank' => $bankCode,
            ];
        } else if ($this->payment_method === 'credit_card') {
            $params['enabled_payments'] = ['credit_card'];
            
            // Add credit card configuration if a specific card type is selected
            // Tambah konfigurasi kartu kredit jika user memilih tipe kartu kredit yang dipilih
            if ($this->selected_card_type) {
                $params['credit_card'] = [
                    'secure' => true,
                    'channel' => 'migs',
                    'bank' => 'bca',
                    'card_type' => $this->selected_card_type
                ];
            }
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders(['Accept' => 'application/json', 'Content-Type' => 'application/json'])
                ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            $result = $response->json();
            
            if (isset($result['token'])) {
                // Update transaction dengan token dari Midtrans
                $transaction->update([
                    'payment_token' => $result['token'],
                    'payment_url' => $result['redirect_url'] ?? null,
                ]);
                
                // Emit event untuk membuka snap atau redirect ke URL
                $this->dispatch('midtransPayment', [
                    'token' => $result['token'],
                    'redirectUrl' => $result['redirect_url'] ?? null,
                    'paymentMethod' => $this->payment_method,
                    'transactionId' => $transaction->id
                ]);
            } else {
                throw new \Exception('Failed to get Midtrans token');
            }
        } catch (\Exception $e) {
            // Log error
            \Log::error('Midtrans Error: ' . $e->getMessage());
            
            // Update status transaksi menjadi failed
            $transaction->update(['status' => 'failed']);
            
            // Notify user
            session()->flash('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }

    public function handlePaymentCallback($result)
    {
        $orderId = $result['order_id'];
        $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

        if ($result['status_code'] == 200) {
            $transaction->update([
                'status' => 'success',
                'payment_date' => now(),
            ]);
            // Proses lainnya setelah pembayaran berhasil
        } else {
            $transaction->update([
                'status' => 'failed',
            ]);
            // Handle pembayaran gagal
        }

        // Redirect ke halaman transaksi
        return redirect()->route('transactions.index')->with('status', $transaction->status);
    }

    public function render()
    {
        $this->dispatch('preserveMap');
        
        return view('livewire.order.order-form');
    }
}
