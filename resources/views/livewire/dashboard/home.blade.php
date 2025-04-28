<div>
    <!-- tampilan statistik (fixed alignment) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-5 h-full">
            <div class="flex items-center h-full">
                <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3 flex items-center justify-center w-14 h-14">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-5 flex flex-col">
                    <p class="text-gray-500 text-sm mb-1">Total Pengguna</p>
                    <h3 class="text-2xl font-semibold text-gray-700">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 h-full">
            <div class="flex items-center h-full">
                <div class="flex-shrink-0 bg-green-500 rounded-lg p-3 flex items-center justify-center w-14 h-14">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-5 flex flex-col">
                    <p class="text-gray-500 text-sm mb-1">Total Mobil</p>
                    <h3 class="text-2xl font-semibold text-gray-700">{{ $totalCars }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 h-full">
            <div class="flex items-center h-full">
                <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3 flex items-center justify-center w-14 h-14">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-5 flex flex-col">
                    <p class="text-gray-500 text-sm mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-semibold text-gray-700">{{ $totalTransactions }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 h-full">
            <div class="flex items-center h-full">
                <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3 flex items-center justify-center w-14 h-14">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-5 flex flex-col">
                    <p class="text-gray-500 text-sm mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-semibold text-gray-700">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- transaksi terkini -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- transaksi terkini -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 font-semibold text-lg">
                Transaksi Terbaru
            </div>
            <div class="p-4">
                @if($recentTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobil</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $transaction->user->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $transaction->car->brand ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->status == 'success')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            @elseif($transaction->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Tertunda
                                                </span>
                                            @elseif($transaction->status == 'processing')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Diproses
                                                    </span>
                                            @elseif($transaction->status == 'cancel')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                @endif
            </div>
        </div>

        <!-- grafik transaksi bulanan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 font-semibold text-lg">
                Transaksi 30 Hari Terakhir
            </div>
            <div class="p-4">
                <div class="w-full h-64">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- script untuk chart.js -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, attempting to initialize chart');

            // Periksa apakah elemen canvas ada
            const canvas = document.getElementById('transactionChart');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }

            const ctx = canvas.getContext('2d');

            try {
                // Ambil data dari Livewire component
                const labels = @js($chartLabels);
                const data = @js($chartData);

                console.log('Chart data:', { labels, data });

                // Inisialisasi chart
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: JSON.parse(labels),
                        datasets: [
                            {
                                label: 'Jumlah Transaksi',
                                data: JSON.parse(data).counts,
                                borderColor: 'rgb(99, 102, 241)',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Total Pendapatan (Rp)',
                                data: JSON.parse(data).amounts,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                beginAtZero: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Jumlah Transaksi'
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Total Pendapatan (Rp)'
                                },
                                grid: {
                                    drawOnChartArea: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if (context.dataset.label === 'Total Pendapatan (Rp)') {
                                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                                        }
                                        return context.dataset.label + ': ' + context.raw;
                                    }
                                }
                            }
                        }
                    }
                });

                console.log('Chart initialized successfully');
            } catch (error) {
                console.error('Error initializing chart:', error);
            }
        });
    </script>
    @endpush
</div>
