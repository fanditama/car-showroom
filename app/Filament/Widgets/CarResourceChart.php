<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CarResourceChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Data Mobil Berdasarkan Tipe';

    protected static string $color = 'info';

    protected function getData(): array
    {
        // Ambil data mobil dikelompokkan berdasarkan tipe
        $carsByType = Car::query()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Mobil',
                    'data' => $carsByType->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#36A2EB', // Biru
                        '#FF6384', // Merah
                        '#FFCE56', // Kuning
                        '#4BC0C0', // Tosca
                        '#9966FF', // Ungu
                        '#FF9F40', // Oranye
                    ],
                ],
            ],
            'labels' => $carsByType->pluck('type')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
