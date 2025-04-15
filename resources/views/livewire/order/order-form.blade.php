<div>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 text-center">Form Pemesanan</h2>
                <p class="mt-2 text-sm text-gray-600 text-center">Silakan lengkapi data pemesanan Anda</p>
            </div>

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <form wire:submit.prevent="submitOrder" class="divide-y divide-gray-200">
                    <!-- Detail Mobil -->
                    <div class="px-6 py-6 sm:p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Detail Mobil</h3>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Model</span>
                                <span class="font-medium">{{ $car->brand }} {{ $car->model }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tahun</span>
                                <span class="font-medium">{{ $car->year }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Harga</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($car->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="px-6 py-6 sm:p-8 space-y-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Data Pemesan</h3>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text"
                                    wire:model.defer="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text"
                                    wire:model.defer="phone"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea
                                wire:model.defer="address"
                                id="addressTextarea"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Masukkan alamat lengkap"></textarea>
                            <p class="mt-1 text-xs text-gray-500">Alamat akan otomatis diisi berdasarkan lokasi yang dipilih pada peta.</p>
                            @error('address')
                                <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- OpenStreetMap Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tentukan Lokasi di Peta</label>

                            <div id="map"
                                 wire:ignore
                                 style="height: 400px; width: 100%; min-height: 400px; border: 1px solid #ddd; border-radius: 0.5rem;">
                            </div>

                            <div class="flex items-center mt-2">
                                <button type="button"
                                        id="findMyLocation"
                                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Gunakan Lokasi Saya
                                </button>

                                <div class="ml-4 text-sm text-gray-600">
                                    <span id="locationStatus">
                                        @if($latitude && $longitude)
                                            Lokasi sudah ditentukan
                                        @else
                                            Klik pada peta untuk menentukan lokasi
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Hidden inputs untuk menyimpan koordinat -->
                            <input type="hidden" id="latitude" wire:model.defer="latitude">
                            <input type="hidden" id="longitude" wire:model.defer="longitude">

                            @error('latitude')
                                <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <select wire:model.live="payment_method" id="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Pilih metode pembayaran</option>
                                <option value="cash">Tunai</option>
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="credit_card">Kartu Kredit</option>
                            </select>
                            @error('payment_method') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-4">
                        <a href="{{ url()->previous() }}"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 disabled:opacity-75">
                            <span wire:loading.remove wire:target="submitOrder">
                                Konfirmasi Pesanan
                            </span>
                            <span wire:loading wire:target="submitOrder" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            @if(session('error'))
                <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <!-- Script untuk integrasi OpenStreetMap -->
    <script>
        // Variabel global agar peta tidak hilang saat re-render
        var map;
        var marker;
        var isMapInitialized = false;
        var pendingLocation = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
        });

        document.addEventListener('livewire:load', function() {
            initializeMap();

            // Listen untuk event dari Livewire
            window.Livewire.on('locationUpdated', function() {
                initializeMap();

                console.log('Received locationUpdated event');
                if (map) {
                    map.invalidateSize();

                    // Jika ada lokasi yang tertunda, set marker ke lokasi tersebut
                    if (pendingLocation) {
                        updateMarkerPosition(pendingLocation.lat, pendingLocation.lng);
                        pendingLocation = null;
                    }
                } else {
                    console.warn('Map not available when locationUpdated event fired');
                    setTimeout(initializeMap, 500);
                }
            });

            window.addEventListener('preserveMap', function() {
                console.log('Preserving map after rendering');
                preserveMap();
            });

            // Event untuk redirect ke halaman transaksi setelah submit
            window.Livewire.on('orderCreated', function(transactionId) {
                console.log('Order created, redirecting to transaction page:', transactionId);
                window.location.href = '/transactions/' + transactionId;
            });
        });

        // Menangani kasus ketika Livewire melakukan update komponen
        document.addEventListener('livewire:update', function() {
            preserveMap();
        });

        function preserveMap() {
            console.log('Livewire update detected, checking map status');

            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.warn('Map element not found after Livewire update');
                return;
            }

            if (!map || !map._container) {
                console.log('Map needs to be reinitialized');
                setTimeout(initializeMap, 300);
            } else if (map._container !== mapElement) {
                console.log('Map container changed, reattaching map');
                map._container = mapElement;
                map.invalidateSize();
            } else {
                console.log('Map preserved, refreshing size');
                map.invalidateSize();
            }
        }

        function initializeMap() {
            console.log('Initializing map');

            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.error('Map element not found during initialization');
                return;
            }

            // Jika peta sudah ada, cukup refresh size
            if (map && map._container) {
                console.log('Map already exists, refreshing size');
                setTimeout(function() {
                    map.invalidateSize();
                }, 200);
                return;
            }

            // Koordinat default (Indonesia)
            const defaultLat = -6.200000;
            const defaultLng = 106.816666;

            // Gunakan koordinat dari Livewire jika ada
            let initialLat = {{ $latitude ?? 'null' }};
            let initialLng = {{ $longitude ?? 'null' }};

            initialLat = initialLat !== null ? parseFloat(initialLat) : defaultLat;
            initialLng = initialLng !== null ? parseFloat(initialLng) : defaultLng;

            console.log('Creating new map with coords:', initialLat, initialLng);

            try {
                // Buat peta baru
                map = L.map(mapElement, {
                    center: [initialLat, initialLng],
                    zoom: 13
                });

                // Tambahkan tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Pastikan peta dirender dengan ukuran yang benar
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);

                // Tambahkan marker jika sudah ada koordinat
                if (initialLat !== defaultLat || initialLng !== defaultLng) {
                    placeMarker(initialLat, initialLng);
                }

                // Event click pada peta
                map.on('click', function(e) {
                    placeMarker(e.latlng.lat, e.latlng.lng);
                });

                // Button "Gunakan Lokasi Saya"
                setupGeolocationButton();

                isMapInitialized = true;
                console.log('Map successfully initialized');
            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }

        function setupGeolocationButton() {
            const findMyLocationBtn = document.getElementById('findMyLocation');
            if (!findMyLocationBtn) {
                console.warn('Find location button not found');
                return;
            }

            // Hapus event listener lama jika ada
            const newButton = findMyLocationBtn.cloneNode(true);
            findMyLocationBtn.parentNode.replaceChild(newButton, findMyLocationBtn);

            newButton.addEventListener('click', function(e) {
                // Prevent default untuk menghindari refresh halaman
                e.preventDefault();

                if (!navigator.geolocation) {
                    updateLocationStatus('Browser Anda tidak mendukung geolocation.');
                    return;
                }

                updateLocationStatus('Mencari lokasi Anda...');

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        console.log('Geolocation success:', lat, lng);

                        // Simpan lokasi tertunda untuk digunakan setelah render ulang
                        pendingLocation = { lat, lng };

                        // Perbarui peta jika ada
                        if (map) {
                            map.setView([lat, lng], 16);
                            placeMarker(lat, lng);
                        } else {
                            console.warn('Map not available when geolocation succeeded');
                        }

                        updateLocationStatus('Lokasi ditemukan, mengupdate alamat...');
                    },
                    function(error) {
                        console.error('Geolocation error:', error);
                        updateLocationStatus('Gagal mendapatkan lokasi: ' + getGeolocationErrorMessage(error));
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }

        // function handle pesan error saat mendapatkan lokasi
        function getGeolocationErrorMessage(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    return "Akses lokasi ditolak oleh pengguna.";
                case error.POSITION_UNAVAILABLE:
                    return "Informasi lokasi tidak tersedia.";
                case error.TIMEOUT:
                    return "Permintaan lokasi timeout.";
                default:
                    return "Terjadi kesalahan: " + error.message;
            }
        }

        function placeMarker(lat, lng) {
            if (!map) {
                console.warn('Map not available when placing marker');
                return;
            }

            console.log('Placing marker at:', lat, lng);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function() {
                    const pos = marker.getLatLng();
                    updateCoordinates(pos.lat, pos.lng);
                });
            }

            updateCoordinates(lat, lng);
        }

        function updateMarkerPosition(lat, lng) {
            if (!map) {
                console.warn('Map not available when updating marker position');
                return;
            }

            console.log('Updating marker position to:', lat, lng);

            map.setView([lat, lng], map.getZoom() || 16);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function() {
                    const pos = marker.getLatLng();
                    updateCoordinates(pos.lat, pos.lng);
                });
            }
        }

        function updateCoordinates(lat, lng) {
            console.log('Updating coordinates to:', lat, lng);
            updateLocationStatus('Memperbarui lokasi...');

            // Update koordinat di Livewire
            @this.set('latitude', lat);
            @this.set('longitude', lng);

            // Lakukan reverse geocoding
            fetchAddress(lat, lng);
        }

        function updateLocationStatus(message) {
            const statusElement = document.getElementById('locationStatus');
            if (statusElement) {
                statusElement.textContent = message;
            }
        }

        function fetchAddress(lat, lng) {
            console.log('Fetching address for:', lat, lng);

            // URL nominatim
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;

            fetch(url, {
                headers: {
                    'Accept-Language': 'id',
                    'User-Agent': 'CarShowroomApp/1.0'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received geocoding data:', data);

                if (data && data.display_name) {
                    const address = data.display_name;
                    console.log('Setting address to:', address);

                    // Update alamat di Livewire
                    @this.set('address', address);

                    updateLocationStatus('Lokasi dan alamat diperbarui');
                }
            })
            .catch(error => {
                console.error('Error fetching address:', error);
                updateLocationStatus('Lokasi diperbarui, gagal mendapatkan alamat');
            });
        }
    </script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('orderCreated', (transactionId) => {
                console.log('Order created, redirecting to transaction page:', transactionId);
                // Tambah delay kecil untuk transisi menuju halaman transaksi
                setTimeout(() => {
                    window.location.href = '/transactions/' + transactionId;
                }, 500);
            });
        });
    </script>
    @endpush
</div>
