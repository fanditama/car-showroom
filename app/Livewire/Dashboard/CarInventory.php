<?php

namespace App\Livewire\Dashboard;

use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CarInventory extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $brand = '';
    public $model = '';
    public $year = '';
    public $price = '';
    public $type = '';
    public $description = '';
    public $image;

    public $search = '';
    public $sortField = 'brand';
    public $sortDirection = 'asc';

    public $editMode = false;
    public $currentCarId = null;
    public $showAddModal = false;
    public $showDeleteModal = false;
    public $carToDelete = null;

    protected $rules = [
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1900|max:2099',
        'price' => 'required|numeric|min:0',
        'type' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:10024',
    ];

    public function message()
    {
        return [
            'brand.required' => 'Brand tidak boleh kosong.',
            'model.required' => 'Model tidak boleh kosong.',
            'year.required' => 'Tahun tidak boleh kosong.',
            'price.required' => 'Harga tidak boleh kosong.',
            'type.required' => 'Tipe mobil tidak boleh kosong.',
            'image.image' => 'Tipe file harus dalam bentuk gambar.',
            'image.max' => 'Ukuran maksimal file gambar tidak boleh melebihi 10 MB.',
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
        $this->editMode = false;
    }

    public function edit($carId)
    {
        $this->editMode = true;
        $this->currentCarId = $carId;

        $car = Car::findOrFail($carId);
        $this->brand = $car->brand;
        $this->model = $car->model;
        $this->year = $car->year;
        $this->price = $car->price;
        $this->type = $car->type;
        $this->description = $car->description;

        $this->showAddModal = true;
        $this->editMode = true;
    }

    public function delete($carId)
    {
        $this->currentCarId = $carId;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        $car = Car::findOrFail($this->currentCarId);

        // hapus gambar jika ada
        if ($car->image_url && Storage::disk('public')->exists($car->image_url)) {
            Storage::disk('public')->delete($car->image_url);
        }

        $car->delete();
        $this->showDeleteModal = false;
        $this->dispatch('toast', 'Mobil berhasil dihapus!', 'success');
    }

    public function store()
    {
        $this->validate();

        $imageUrl = null;
        if ($this->image) {
            $imageUrl = $this->image->store('car-images', 'public');
        }

        if ($this->editMode) {
            $car = Car::findOrFail($this->currentCarId);

            // jika gambar yang baru diupload, hapus gambar lama
            if ($this->image) {
                if ($car->image_url && Storage::disk('public')->exists($car->image_url)) {
                    Storage::disk('public')->delete($car->image_url);
                }

                $imagePath = $this->image->store('cars', 'public');
                $car->image_url = $imagePath;
            }

            $car->update([
                'brand' => $this->brand,
                'model' => $this->model,
                'year' => $this->year,
                'price' => $this->price,
                'type' => $this->type,
                'description' => $this->description,
            ]);

            $this->dispatch('toast', 'Data mobil berhasil diperbarui!', 'success');
        } else {
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('cars', 'public');
            }

            Car::create([
                'brand' => $this->brand,
                'model' => $this->model,
                'year' => $this->year,
                'price' => $this->price,
                'type' => $this->type,
                'description' => $this->description,
                'image_url' => $imagePath,
            ]);

            $this->dispatch('toast', 'Mobil baru berhasil ditambahkan!', 'success');
        }

        $this->resetForm();
        $this->showAddModal = false;
    }

    public function resetForm()
    {
        $this->reset(['brand', 'model', 'year', 'price', 'type', 'description', 'image', 'editMode', 'currentCarId']);
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showAddModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $cars = Car::where('brand', 'like', '%'.$this->search.'%')
                    ->orWhere('model', 'like', '%'.$this->search.'%')
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate(10);

        return view('livewire.dashboard.car-inventory', [
            'cars' => $cars
        ]);
    }
}
