<?php

namespace App\Livewire\Home;

use App\Models\Car;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{
    public $categories;
    public $currentType;
    public $cartItemCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->categories = Car::select('type')->distinct()->pluck('type');
        $this->currentType = request()->query('type');
        $this->updateCartCount();
    }

    public function updateCartCount($data = null)
    {
        if (Auth::check()) {
            if (isset($data['count'])) {
                $this->cartItemCount = $data['count'];
            } else {
                $this->cartItemCount = Cart::where('user_id', Auth::id())->count();
            }
        } else {
            $this->cartItemCount = 0;
        }
    }

    public function logout()
    {
        Auth::logout();
        $this->cartItemCount = 0;

        return redirect('/');
    }

    public function render()
    {
        return view('livewire.home.header');
    }
}
