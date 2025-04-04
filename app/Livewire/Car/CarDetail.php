<?php

namespace App\Livewire\Car;

use App\Models\Car;
use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CarDetail extends Component
{
    public Car $car;
    public $showSharePopup = false;
    public $linkCopied = false;
    public $isInCart = false;

    public function mount(Car $car)
    {
        $this->car = $car;
        if (Auth::check()) {
            $this->isInCart = Cart::where('user_id', Auth::id())
                ->where('car_id', $this->car->id)
                ->exists();
        }
    }

    public function toggleSharePopup()
    {
        $this->showSharePopup = !$this->showSharePopup;
        $this->linkCopied = false;
    }

    public function shareToWhatsApp()
    {
        $url = url()->current();
        $text = "Lihat mobil {$this->car->brand} {$this->car->model} ({$this->car->year}) di sini: ";
        $whatsappUrl = "https://wa.me/?text=" . urlencode($text . $url);

        $this->dispatch('openUrl', url: $whatsappUrl);
    }

    public function shareToFacebook()
    {
        $url = url()->current();
        $facebookUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url);

        $this->dispatch('openUrl', url: $facebookUrl);
    }

    public function shareToInstagram()
    {
        // Instagram doesn't support direct sharing via URL, so we'll show a message
        $this->dispatch('showInstagramHelp');
    }

    public function order()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return redirect()->route('order.form', ['car' => $this->car->id]);
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            Cart::updateOrCreate(
                ['user_id' => Auth::id(), 'car_id' => $this->car->id],
                ['user_id' => Auth::id(), 'car_id' => $this->car->id],
            );

            $this->isInCart = true;
            // ambil keranjang yang baru dihitung
            $cartCount = Cart::where('user_id', Auth::id())->count();
            $this->dispatch('cartUpdated', count: $cartCount);
            $this->dispatch('notify', ['message' => 'Mobil berhasil ditambahkan ke keranjang!', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Gagal menambahkan ke keranjang!', 'type' => 'error']);
        }
    }

    public function removeFromCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Cart::where('user_id', Auth::id())
            ->where('car_id', $this->car->id)
            ->delete();

        $this->isInCart = false;
        //ambil keranjang yang baru dihitung
        $cartCount = Cart::where('user_id', Auth::id())->count();
        $this->dispatch('cartUpdated', count: $cartCount);
        $this->dispatch('notify', ['message' => 'Mobil dihapus dari keranjang', 'type' => 'info']);
    }

    public function render()
    {
        return view('livewire.car.car-detail');
    }
}
