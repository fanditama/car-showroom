<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionDetail extends Component
{
    public Transaction $transaction;
    public bool $hasMap;
    public $snapToken;

    public function mount(Transaction $transaction)
    {
        // cek apakah user sudah login
        if (Auth::id() !== $transaction->user_id) {
            abort(403);
        }
        $this->transaction = $transaction;
        $this->hasMap = !empty($transaction->latitude) && !empty($transaction->longitude);

        // jika transaksi pending dan payment method online, generate snap token
        if ($transaction->status === 'pending' && in_array($transaction->payment_method, ['transfer_bank', 'credit_card'])) {
            $this->generateSnapToken();
        }
    }

    public function generateSnapToken()
    {
        try {
            // atur midtrans konfigurasi
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');
            
            $car = $this->transaction->car;
            
            $params = [
                'transaction_details' => [
                    'order_id' => 'CAR-' . $this->transaction->id . '-' . time(),
                    'gross_amount' => (int) $this->transaction->total_amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'item_details' => [
                    [
                        'id' => $car->id,
                        'price' => (int) $car->price,
                        'quantity' => 1,
                        'name' => $car->brand . ' ' . $car->model . ' (' . $car->year . ')',
                    ]
                ],
            ];
            
            $this->snapToken = Snap::getSnapToken($params);
            
            // simpan snap token ke transaksi untuk referensi future
            $this->transaction->update(['snap_token' => $this->snapToken]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Gagal membuat token pembayaran: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function processPayment()
    {
        if ($this->transaction->payment_method === 'cash') {
            // untuk pembayaran cash (tunai), update status ke processing 
            $this->transaction->status = 'processing';
            $this->transaction->save();
            
            $this->dispatch('notify', [
                'message' => 'Pesanan diproses. Pembayaran akan dilakukan saat mobil sudah dikirim.',
                'type' => 'success'
            ]);
            
            $this->dispatch('refreshTransactionStatus');
        } else {
            // untuk pembayaran online, client-side midtrans akan meng-handle pembayaran
            // snap token harus sudah di-generate in mount() method
            if (empty($this->snapToken)) {
                $this->generateSnapToken();
            }
        }
    }

    public function handlePaymentCallback($result)
    {
        // tangani callback dari pembayaran midtrans
        if (isset($result['status_code']) && $result['status_code'] == 200) {
            $this->transaction->status = 'success';
            $this->transaction->payment_date = now()->format('d-m-Y H:i:s');
            $this->transaction->payment_details = json_encode($result);
            $this->transaction->save();
            
            $this->dispatch('notify', [
                'message' => 'Pembayaran berhasil!',
                'type' => 'success'
            ]);
            
            $this->dispatch('refreshTransactionStatus');
        } else {
            $this->transaction->status = 'failed';
            $this->transaction->payment_details = json_encode($result);
            $this->transaction->save();
            
            $this->dispatch('notify', [
                'message' => 'Pembayaran gagal. Silakan coba lagi.',
                'type' => 'error'
            ]);
            
            $this->dispatch('refreshTransactionStatus');
        }
    }

    public function cancelTransaction()
    {
        if ($this->transaction->status !== 'pending') {
            $this->dispatch('notify', [
                'message' => 'Hanya transaksi dengan status menunggu yang dapat dibatalkan.',
                'type' => 'error'
            ]);
            return;
        }

        $this->transaction->status = 'cancel';
        $this->transaction->save();

        $this->dispatch('notify', [
            'message' => 'Transaksi berhasil dibatalkan.',
            'type' => 'success'
        ]);

        $this->dispatch('refreshTransactionStatus');
    }

    public function render()
    {
        return view('livewire.transaction.transaction-detail');
    }
}
