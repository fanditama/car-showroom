<div>
    <div class="container mx-auto">
        <!-- Image Gallery Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                <!-- Main Image Display -->
                <div class="md:col-span-2">
                    <div class="relative rounded-lg overflow-hidden">
                        @if($car->image_url)
                            <img src="{{ $car->image_url }}"
                                 alt="{{ $car->brand }} {{ $car->model }}"
                                 class="w-full h-[400px] object-cover rounded-lg">
                        @else
                            <div class="w-full h-[400px] bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-lg">
                                <i class="fas fa-car text-6xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Car Information Panel -->
                <div class="md:col-span-1 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                            {{ $car->brand }} {{ $car->model }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            {{ $car->year }} • {{ $car->type }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                            <p class="text-3xl font-bold text-blue-500">
                                Rp&nbsp;{{ number_format($car->price, 0, '.', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Quick Specs -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Merek</span>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $car->brand }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Model</span>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $car->model }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Tahun</span>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $car->year }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Tipe</span>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $car->type }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if(auth()->check() && auth()->user()->role === 'user')
                    <div class="space-y-3">
                        <button wire:click="order" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shop text-xl mr-2"></i>
                            <span class="text-base">Pesan Sekarang</span>
                        </button>

                        @if($isInCart)
                            <button wire:click="removeFromCart" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                                <i class="fas fa-cart-shopping text-xl mr-2"></i>
                                <span class="text-base">Hapus dari Keranjang</span>
                            </button>
                        @else
                            <button wire:click="addToCart" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                                <i class="fas fa-cart-shopping text-xl mr-2"></i>
                                <span class="text-base">Simpan ke Keranjang</span>
                            </button>
                        @endif
                    </div>
                    @elseif(!auth()->check())
                    <div class="space-y-3">
                        <button wire:click="order" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shop text-xl mr-2"></i>
                            <span class="text-base">Pesan Sekarang</span>
                        </button>

                        <button wire:click="addToCart" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cart-shopping text-xl mr-2"></i>
                            <span class="text-base">Simpan ke Keranjang</span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Deskripsi</h2>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ $car->description }}
            </p>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center mb-8">
            <a href="/" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                              text-gray-700 dark:text-white px-6 py-3 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke halaman utama
            </a>
            <button wire:click="toggleSharePopup" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center">
                <i class="fas fa-share-alt mr-2"></i>
                Bagikan
            </button>
        </div>
        <!-- Share Popup -->
        @if($showSharePopup)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4 relative">
                <!-- Close button -->
                <button wire:click="toggleSharePopup" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Bagikan Mobil Ini</h3>

                <div class="grid grid-cols-3 gap-4">
                    <!-- WhatsApp -->
                    <button wire:click="shareToWhatsApp" class="flex flex-col items-center justify-center p-4 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                        <i class="fab fa-whatsapp text-3xl mb-2"></i>
                        <span class="text-sm">WhatsApp</span>
                    </button>

                    <!-- Facebook -->
                    <button wire:click="shareToFacebook" class="flex flex-col items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fab fa-facebook text-3xl mb-2"></i>
                        <span class="text-sm">Facebook</span>
                    </button>

                    <!-- Instagram -->
                    <button wire:click="shareToInstagram" class="flex flex-col items-center justify-center p-4 bg-gradient-to-r from-purple-500 via-pink-500 to-yellow-500 hover:from-purple-600 hover:via-pink-600 hover:to-yellow-600 text-white rounded-lg transition-colors">
                        <i class="fab fa-instagram text-3xl mb-2"></i>
                        <span class="text-sm">Instagram</span>
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
