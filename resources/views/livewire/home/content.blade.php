<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-100">
            {{ $type ? ucfirst($type) . ' Mobil' : 'Semua Mobil' }}
        </h2>

        <!-- Filter & Sort Options -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 w-full sm:w-auto">
            <select wire:model.live="sortBy" class="w-full sm:w-auto bg-gray-700 text-white rounded-lg px-4 py-2 border border-gray-600">
                <option value="default">Urutkan berdasarkan Harga</option>
                <option value="price_asc">Terendah ke Tertinggi</option>
                <option value="price_desc">Tertinggi ke Terendah</option>
            </select>
        </div>
    </div>

    @if($cars->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 bg-gray-800 rounded-lg">
            <i class="fas fa-car text-gray-600 text-5xl mb-4"></i>
            <p class="text-gray-400 text-xl text-center px-4">Tidak ditemukan mobil pada kategori ini.</p>
            <a href="/" class="mt-4 text-blue-500 hover:text-blue-400">
                Kembali ke Semua Mobil
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @foreach($cars as $car)
            <div class="bg-gray-800 rounded-xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
                <!-- Image Section -->
                <div class="relative h-48 sm:h-56">
                    @if($car->image_url)
                        <img src="{{ $car->image_url }}"
                             alt="{{ $car->brand }} {{ $car->model }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                            <i class="fas fa-car text-gray-600 text-4xl"></i>
                        </div>
                    @endif

                    <!-- Price Badge -->
                    <div class="absolute top-2 sm:top-4 right-2 sm:right-4 bg-blue-500 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-bold">
                        Rp&nbsp;{{ number_format($car->price, 0, '.', '.') }}
                    </div>

                    <!-- Type Badge -->
                    <div class="absolute bottom-2 sm:bottom-4 left-2 sm:left-4 bg-gray-900 bg-opacity-75 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">
                        {{ $car->type }}
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-4 sm:p-6 flex flex-col flex-grow">
                    <!-- Title Section -->
                    <div class="mb-4">
                        <h3 class="text-lg sm:text-xl font-bold text-white mb-1 truncate" title="{{ $car->brand }} {{ $car->model }}">
                            {{ $car->brand }} {{ $car->model }}
                        </h3>
                        <p class="text-gray-400 text-xs sm:text-sm">
                            <i class="fas fa-calendar-alt mr-2"></i>{{ $car->year }}
                        </p>
                    </div>

                    <!-- Specifications -->
                    <div class="grid grid-cols-2 gap-2 sm:gap-4 mb-4">
                        <div class="bg-gray-700 p-2 sm:p-3 rounded-lg">
                            <p class="text-gray-400 text-xs sm:text-sm">Merek</p>
                            <p class="text-white font-semibold text-sm sm:text-base truncate">{{ $car->brand }}</p>
                        </div>
                        <div class="bg-gray-700 p-2 sm:p-3 rounded-lg">
                            <p class="text-gray-400 text-xs sm:text-sm">Model</p>
                            <p class="text-white font-semibold text-sm sm:text-base truncate">{{ $car->model }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-gray-300 text-xs sm:text-sm line-clamp-2">
                            {{ $car->description }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-700">
                        <a href="{{ route('cars.show', $car->id) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg flex items-center transition-colors duration-300 text-sm sm:text-base">
                            <span>Detail Tampilan</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>

                        <button class="text-gray-400 hover:text-red-500 transition-colors duration-300 p-2">
                            <i class="far fa-heart text-lg sm:text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $cars->links() }}
        </div>
    @endif
</div>
