<?php

namespace App\Livewire\Order;

use App\Models\Car;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderForm extends Component
{
    public Car $car;
    public $name;
    public $phone;
    public $address;
    public $mapReady = true;
    public $payment_method;
    public $latitude = null;
    public $longitude = null;

    protected $rules = [
        'name' => 'required|min:3',
        'phone' => 'required|numeric',
        'address' => 'required|min:10',
        'payment_method' => 'required|in:cash,transfer_bank,credit_card',
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
    }

    public function updatedPaymentMethod($value)
    {
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

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'car_id' => $this->car->id,
            'transaction_date' => now()->format('d-m-Y H:i:s'),
            'total_amount' => $this->car->price,
            'payment_method' => $this->payment_method,
            'status' => 'pending', // semua order set ke pending
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'order_address' => $this->address,
            'order_id' => 'ORDER-' . time() . '-' . Auth::id(),
        ]);

        // Redirect to transaction detail page
        $this->dispatch('orderCreated', $transaction->id);
    }

    public function render()
    {
        $this->dispatch('preserveMap');
        
        return view('livewire.order.order-form');
    }
}
