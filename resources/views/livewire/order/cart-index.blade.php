<div>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

        @if(count($cartItems) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-4">
                                    @if($item->car->image_url)
                                        <img src="{{ $item->car->image_url }}" alt="{{ $item->car->brand }} {{ $item->car->model }}" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded">
                                            <i class="fas fa-car text-gray-400"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <a href="{{ route('cars.show', $item->car) }}" class="font-semibold text-gray-800 dark:text-white hover:text-blue-500">
                                            {{ $item->car->brand }} {{ $item->car->model }}
                                        </a>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->car->year }} • {{ $item->car->type }}</p>
                                        <p class="text-blue-500 font-semibold">Rp {{ number_format($item->car->price, 0, '.', '.') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('order.form', $item->car) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                                        <i class="fas fa-shopping-cart mr-2"></i> Pesan
                                    </a>
                                    <button wire:click="removeItem({{ $item->id }})" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                                        <i class="fas fa-trash mr-2"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="/" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <i class="fas fa-shopping-cart text-gray-400 text-5xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Keranjang Anda kosong</h2>
                    <p class="text-gray-600 dark:text-gray-400">Tambahkan mobil ke keranjang untuk melanjutkan</p>
                    <a href="/" class="mt-4 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg inline-flex items-center">
                        <i class="fas fa-search mr-2"></i> Cari Mobil
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
