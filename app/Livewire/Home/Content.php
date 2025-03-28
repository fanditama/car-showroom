<?php

namespace App\Livewire\Home;

use App\Models\Car;
use Livewire\Component;

class Content extends Component
{
    public $type;

    public function mount()
    {
        $this->type = request()->query('type');
    }

    public function render()
    {
        // query untuk mengambil data mobil
        $carsQuery = Car::query();
        
        // Jika type tidak kosong, maka filter berdasarkan type
        if ($this->type) {
            $carsQuery->where('type', $this->type);
        }
        
        // ambil data mobil
        $cars = $carsQuery->get();

        return view('livewire.home.content', [
            'cars' => $cars,
            'type' => $this->type
        ]);
    }
}
