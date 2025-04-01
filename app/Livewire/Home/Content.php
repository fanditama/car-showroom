<?php

namespace App\Livewire\Home;

use App\Models\Car;
use Livewire\Component;
use Livewire\WithPagination;

class Content extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-tailwind';

    public $type;
    public $sortBy = 'newest';

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function mount($type = null)
    {
        $this->type = $type ?? request()->query('type');
    }

    public function render()
    {
        // query untuk mengambil data mobil
        $carsQuery = Car::query();

        // Jika type tidak kosong, maka filter berdasarkan type
        if ($this->type) {
            $carsQuery->where('type', $this->type);
        }

        // implementasi sorting berdasarkan pilihan user
        switch ($this->sortBy) {
            case 'price_asc':
                $carsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $carsQuery->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $carsQuery->latest();
                break;
        }

        // ambil data mobil dengan pagination
        $cars = $carsQuery->paginate(9);

        return view('livewire.home.content', [
            'cars' => $cars,
            'type' => $this->type
        ]);
    }
}
