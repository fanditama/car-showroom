<?php

namespace App\Livewire\Order;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartIndex extends Component
{
    public $cartItems = [];

    protected $listeners = ['cartUpdated' => 'refreshCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cartItems = Cart::where('user_id', Auth::id())
            ->with('car')
            ->get();
    }

    public function removeItem($cartId)
    {
        try {
            $cart = Cart::where('id', $cartId)
                ->where('user_id', Auth::id())
                ->with('car')
                ->first();

            if (!$cart) {
                $this->dispatch('notify', [
                    'message' => 'Item tidak ditemukan',
                    'type' => 'error'
                ]);
                return;
            }

            $carInfo = $cart->car->brand . ' ' . $cart->car->model;
            $cart->delete();

            $this->refreshCart();

            // ubah cart count
            $cartCount = Cart::where('user_id', Auth::id())->count();
            $this->dispatch('cartUpdated', ['count' => $cartCount]);

            // kirim notifikasi
            $this->dispatch('notify', [
                'message' => $carInfo . ' berhasil dihapus dari keranjang',
                'type' => 'info'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Gagal menghapus item: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.order.cart-index');
    }
}
