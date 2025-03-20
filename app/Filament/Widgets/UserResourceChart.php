<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class UserResourceChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Data User';

    protected static string $color = 'info';

    // Tambahkan filter agar user bisa memilih tampilan chart
    public function getFilters(): array
    {
        return [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
        ];
    }

    protected function getData(): array
    {
        // Ambil filter (default 'daily' jika belum dipilih)
        $filter = $this->filter ?? 'daily';

        // Tentukan rentang waktu dan metode grouping
        switch ($filter) {
            case 'weekly':
                $start = now()->subWeek();
                $groupBy = 'perDay';
                break;
            case 'monthly':
                $start = now()->subMonth();
                $groupBy = 'perDay';
                break;
            case 'yearly':
                $start = now()->subYear();
                $groupBy = 'perMonth';
                break;
            default: // per-hari
                $start = now()->startOfMonth();
                $groupBy = 'perDay';
        }

        // Buat tren sesuai groupBy
        $trendQuery = Trend::model(User::class)
            ->between(start: $start, end: now());

        // Jalankan grouping berdasarkan hasil switch
        $data = match ($groupBy) {
            'perDay' => $trendQuery->perDay()->count(),
            'perMonth' => $trendQuery->perMonth()->count(),
            default => $trendQuery->perDay()->count(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'User',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
