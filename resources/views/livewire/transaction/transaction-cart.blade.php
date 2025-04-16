<div>
    <div class="w-full lg:max-w-4xl max-w-[335px] bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Riwayat Pemesanan</h1>

        @if($transactions->isEmpty())
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Anda belum memiliki riwayat pemesanan.</p>
                <a href="{{ url('/') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                    Lihat Mobil
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($transactions as $transaction)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Order ID:</span>
                                <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $transaction->order_id }}</span>
                            </div>
                            <div class="mt-2 sm:mt-0 flex items-center space-x-4">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal:</span>
                                    <span class="ml-1 text-gray-900 dark:text-white">{{ $transaction->transaction_date->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($transaction->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($transaction->status === 'completed') bg-green-100 text-green-800
                                        @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($transaction->car && $transaction->car->image_url)
                                        <img src="{{ asset('storage/' . $transaction->car->image_url) }}" alt="{{ $transaction->car->name }}" class="w-16 h-12 object-cover rounded-md">
                                    @else
                                        <div class="w-16 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $transaction->car ? $transaction->car->name : 'Mobil tidak tersedia' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($transaction->car)
                                                {{ $transaction->car->make }} {{ $transaction->car->type }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Metode: {{ ucfirst($transaction->payment_method) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Detail Pemesanan</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @if($transaction->order_address)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Alamat Pengiriman:</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->order_address }}</p>
                                    </div>
                                    @endif

                                    @if($transaction->selected_bank)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Bank:</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ strtoupper($transaction->selected_bank) }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between items-center">
                                <a href="{{ route('transactions.show', $transaction) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <span>Lihat Detail</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                
                                @if($transaction->payment_url && $transaction->status === 'pending')
                                <a href="{{ $transaction->payment_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                    Lanjutkan Pembayaran
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
