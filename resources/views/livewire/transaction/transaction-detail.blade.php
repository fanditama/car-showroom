<div>
    @if (session()->has('success'))
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (session()->has('error'))
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-6">
                <a href="{{ url('/') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke daftar mobil
                </a>
            </div>

            <!-- Transaction Header -->
            <div class="bg-white rounded-t-lg shadow-sm px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Detail Transaksi</h1>
                    <p class="text-sm text-gray-500">ID: {{ $transaction->id }}</p>
                </div>
                <div>
                    @if($transaction->status == 'pending')
                        <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300">
                            Menunggu
                        </span>
                    @elseif($transaction->status == 'processing')
                        <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800 border border-blue-300">z
                            Diproses
                        </span>
                    @elseif($transaction->status == 'success')
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 border border-green-300">
                            Sukses
                        </span>
                    @elseif($transaction->status == 'failed')
                        <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 border border-red-300">
                            Gagal
                        </span>
                    @elseif($transaction->status == 'cancel')
                        <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800 border border-gray-300">
                            Dibatalkan
                        </span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-b-lg shadow-sm overflow-hidden mb-6">
                <!-- Transaction Info -->
                <div class="border-t border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Transaksi</h3>

                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $transaction->transaction_date->format('d M Y, H:i') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Pembayaran</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status Pembayaran</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($transaction->status == 'pending')
                                    <span class="text-yellow-600">Menunggu Pembayaran</span>
                                @elseif($transaction->status == 'processing')
                                    <span class="text-blue-600">Sedang Diproses</span>
                                @elseif($transaction->status == 'success')
                                    <span class="text-green-600">Pembayaran Berhasil</span>
                                @elseif($transaction->status == 'failed')
                                    <span class="text-red-600">Pembayaran Gagal</span>
                                @elseif($transaction->status == 'cancel')
                                    <span class="text-gray-600">Transaksi Dibatalkan</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Metode Pembayaran</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($transaction->payment_method == 'cash')
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Tunai
                                    </span>
                                @elseif($transaction->payment_method == 'transfer_bank')
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Transfer Bank
                                    </span>
                                @elseif($transaction->payment_method == 'credit_card')
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Kartu Kredit
                                    </span>
                                @else
                                    {{ ucfirst($transaction->payment_method) }}
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>

                <!-- Car Details -->
                <div class="border-t border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Mobil</h3>

                    @if($transaction->car)
                        <div class="flex items-start">
                            @if($transaction->car->image)
                                <div class="flex-shrink-0 rounded-md overflow-hidden w-32 h-24 bg-gray-200">
                                    <img class="w-full h-full object-cover"
                                         src="{{ asset('storage/' . $transaction->car->image) }}"
                                         alt="{{ $transaction->car->brand }} {{ $transaction->car->model }}">
                                </div>
                            @endif

                            <div class="ml-4 flex-1">
                                <h4 class="text-lg font-medium text-gray-900">
                                    {{ $transaction->car->brand }} {{ $transaction->car->model }}
                                </h4>
                                <div class="mt-2 grid grid-cols-1 gap-y-3 sm:grid-cols-2 sm:gap-x-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tahun</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $transaction->car->year }}</dd>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <span class="text-lg font-medium text-green-600">
                                        Rp {{ number_format($transaction->car->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-gray-500">
                            Informasi mobil tidak tersedia
                        </div>
                    @endif
                </div>

                <!-- Shipping Address -->
                <div class="border-t border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Alamat Pengiriman</h3>

                    <p class="text-sm text-gray-700 mb-4">
                        {{ $transaction->order_address }}
                    </p>

                    @if($transaction->latitude && $transaction->longitude)
                        <div id="transaction-map" wire:ignore style="height: 300px; width: 100%; border-radius: 0.5rem;" class="border border-gray-300"></div>
                    @endif
                </div>

                <!-- Payment Actions -->
                @if($transaction->status == 'pending')
                    <div class="border-t border-gray-200 px-6 py-5 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-700">
                            @if($transaction->payment_method == 'cash')
                                Transaksi ini menggunakan metode pembayaran tunai. Pembayaran akan dilakukan saat mobil sudah dikirim ke alamat Anda.
                            @else
                                Transaksi ini belum dibayar. Silakan selesaikan pembayaran untuk melanjutkan proses.
                            @endif
                            </p>
                            <button type="button"
                            @if($transaction->payment_method == 'cash')
                                wire:click="processPayment"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            @else
                                id="pay-button"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @endif
                            >
                            @if($transaction->payment_method == 'cash')
                                Konfirmasi Pesanan
                            @else
                                Bayar Sekarang
                            @endif
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Cancel Button -->
                @if($transaction->status == 'pending')
                <div class="border-t border-gray-200 px-6 py-4 text-right">
                    <button type="button"
                            wire:click="cancelTransaction"
                            wire:confirm="Anda yakin ingin membatalkan transaksi ini?"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-red-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batalkan Pesanan
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        @if($transaction->latitude && $transaction->longitude)
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    initTransactionMap();
                });

                document.addEventListener('livewire:load', function () {
                    initTransactionMap();
                });

                function initTransactionMap() {
                    // Check if map container exists
                    const mapElement = document.getElementById('transaction-map');
                    if (!mapElement) return;

                    // Check if map is already initialized on this element
                    if (mapElement._leaflet_id) return;

                    try {
                        // Initialize the map
                        var map = L.map('transaction-map').setView([{{ $transaction->latitude }}, {{ $transaction->longitude }}], 15);

                        // Add OpenStreetMap tile layer
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        // Add a marker at the delivery location
                        var marker = L.marker([{{ $transaction->latitude }}, {{ $transaction->longitude }}]).addTo(map);

                        // Add a popup with the address
                        marker.bindPopup("{!! addslashes($transaction->order_address) !!}").openPopup();

                        // Fix map display issues after Livewire updates
                        setTimeout(function () {
                            map.invalidateSize();
                        }, 250);

                        // Also invalidate size when tab becomes visible or on resize
                        window.addEventListener('resize', function () {
                            if (map) map.invalidateSize();
                        });
                    } catch (error) {
                        console.error('Error initializing transaction map:', error);
                    }
                }
            </script>
        @endif
        @if($transaction->status == 'pending' && in_array($transaction->payment_method, ['transfer_bank', 'credit_card']))
            <script type="text/javascript">
                document.addEventListener('livewire:load', function () {
                    const payButton = document.getElementById('pay-button');
                    if (payButton) {
                        payButton.addEventListener('click', function () {
                            // Trigger snap popup when "Pay Now" button is clicked
                            if (!'{{ $snapToken }}') {
                                alert('Sistem pembayaran sedang bermasalah. Silakan coba beberapa saat lagi.');
                                return;
                            }

                            snap.pay('{{ $snapToken }}', {
                                onSuccess: function (result) {
                                    /* Success payment handler */
                                    @this.handlePaymentCallback(result);
                                },
                                onPending: function (result) {
                                    /* Pending payment handler */
                                    @this.handlePaymentCallback(result);
                                },
                                onError: function (result) {
                                    /* Error payment handler */
                                    @this.handlePaymentCallback(result);
                                },
                                onClose: function () {
                                    /* Customer closes the payment dialog without completing */
                                    console.log('Payment dialog closed');
                                }
                            });
                        });
                    }
                });

                // If we need to refresh the Snap token when the page is loaded or refreshed
                document.addEventListener('DOMContentLoaded', function () {
                    Livewire.on('refreshTransactionStatus', function () {
                        window.location.reload();
                    });
                });
            </script>
        @endif
    @endpush
</div>
