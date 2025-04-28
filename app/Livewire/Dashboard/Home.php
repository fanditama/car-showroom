<?php

namespace App\Livewire\Dashboard;

use App\Models\Transaction;
use Livewire\Component;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    public $totalUsers;
    public $totalCars;
    public $totalTransactions;
    public $totalRevenue;
    public $recentTransactions;
    public $monthlyTransactions;
    public $chartLabels;
    public $chartData;

    public function mount()
    {
        $this->totalUsers = User::count();
        $this->totalCars = Car::count();
        $this->totalTransactions = Transaction::count();
        $this->totalRevenue = Transaction::where('status', 'success')->sum('total_amount');

        $this->recentTransactions = Transaction::with(['user', 'car'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ambil data transaksi 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $transactions = Transaction::whereBetween(DB::raw('DATE(created_at)'), [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ])
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as total')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // simpan data array selama 30 hari terakhir
        $dates = [];
        $counts = [];
        $amounts = [];

        // isi dengan nilai 0 untuk tanggal yang tidak memiliki transaksi
        for ($i = 0; $i < 30; $i++) {
            $currentDate = Carbon::now()->subDays(29 - $i);
            $formattedDate = $currentDate->format('Y-m-d');
            $displayDate = $currentDate->format('d M');

            $dates[] = $displayDate;

            // Cari transaksi untuk tanggal ini
            $transaction = $transactions->firstWhere('date', $formattedDate);

            if ($transaction) {
                $counts[] = $transaction->count;
                $amounts[] = floatval($transaction->total);
            } else {
                $counts[] = 0;
                $amounts[] = 0;
            }
        }

        $this->chartLabels = json_encode($dates);
        $this->chartData = json_encode([
            'counts' => $counts,
            'amounts' => $amounts
        ]);
    }


    public function render()
    {
        return view('livewire.dashboard.home');
    }
}
