<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionCart extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-tailwind';

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('car')
            ->orderByDesc('transaction_date')
            ->paginate(9);

        return view('livewire.transaction.transaction-cart', [
            'transactions' => $transactions
        ]);
    }
}
