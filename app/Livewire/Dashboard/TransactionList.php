<?php

namespace App\Livewire\Dashboard;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $page = 1;
    public $sortField = 'transaction_date';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $selectedTransaction = null;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function viewTransaction($id)
    {
        $this->selectedTransaction = Transaction::with(['user', 'car'])->find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedTransaction = null;
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->with(['user', 'car'])
            ->when($this->search, function($query) {
                $query->where('order_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('car', function($q) {
                        $q->where('brand', 'like', '%' . $this->search . '%')
                            ->orWhere('model', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.dashboard.transaction-list', [
            'transactions' => $transactions
        ]);
    }
}
