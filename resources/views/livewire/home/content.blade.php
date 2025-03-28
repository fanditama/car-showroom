<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-100">
        {{ $type ? ucfirst($type) . ' Mobil' : 'Semua Mobil' }}
    </h2>

    @php
    $query = \App\Models\Car::latest();
    
    if (request()->query('type')) {
        $query->where('type', request()->query('type'));
    }
    
    $cars = $query->take(6)->get();
    @endphp

    @if($cars->isEmpty())
        <div class="text-center py-10">
            <p class="text-gray-500">Tidak ditemukan mobil pada categori ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cars as $car)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                @if($car->image_url)
                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-gray-400">Tidak ada gambar</span>
                </div>
                @endif
                
                <div class="p-4 flex flex-col flex-grow">
                <h3 class="text-xl font-semibold whitespace-nowrap overflow-hidden text-ellipsis">{{ $car->brand }} {{ $car->model }}</h3>
                <p class="text-gray-500 dark:text-gray-400">{{ $car->year }} • {{ $car->type }}</p>
                <p class="text-lg font-bold mt-2">${{ number_format($car->price, 2) }}</p>
                <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-2">{{ $car->description }}</p>
                
                <div class="mt-auto pt-4">
                    <a href="{{ route('cars.show', $car->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Detail Tampilan
                    </a>
                </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>