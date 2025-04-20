<nav class="w-full lg:max-w-4xl max-w-[335px] bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6">
    <div class="container mx-auto px-4 py-3">
        <!-- Mobile Menu dengan x-data di level parent -->
        <div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-900 dark:text-white hover:underline">Showroom Mobil</a>

                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    @if(!auth()->check() || auth()->user()->role !== 'admin')
                        <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span x-data="{ count: {{ $cartItemCount }} }" x-text="count"
                                @cart-updated.window="count = $event.detail.count"
                                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            </span>
                        </a>
                    @endif

                    <!-- Profile Button -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                @auth
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                @else
                                    <svg class="h-5 w-5 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endauth
                            </div>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                            @auth
                                <span class="block px-4 py-2 text-sm text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                    {{ auth()->user()->name }}
                                </span>
                                <a href="{{ route('account.settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Pengaturan Akun
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('dashboard.home') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Dashboard Admin
                                    </a>
                                @elseif(auth()->user()->role !== 'admin')
                                    <a href="{{ route('transaction.cart') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Riwayat Pemesanan
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Keluar
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Masuk
                                </a>
                                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Daftar Akun
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 class="mt-4">
                <div class="flex flex-col space-y-2">
                    @foreach ($categories as $category)
                        <a href="{{ url('/?type=' . urlencode(strtolower($category))) }}"
                           class="{{ strtolower($currentType) == strtolower($category) ? 'font-bold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }} py-2">
                            {{ strtolower($category) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex lg:items-center lg:justify-between">
            <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-900 dark:text-white hover:underline">Showroom Mobil</a>

            <!-- Categories -->
            <div class="flex-1 flex justify-center">
                <div class="flex space-x-4">
                    @foreach ($categories as $category)
                        <a href="{{ url('/?type=' . urlencode(strtolower($category))) }}"
                           class="{{ strtolower($currentType) == strtolower($category) ? 'font-bold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            {{ strtolower($category) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Desktop Right Section -->
            <div class="flex items-center space-x-4">
                <!-- Cart Icon -->
                @if(!auth()->check() || auth()->user()->role !== 'admin')
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">{{ $cartItemCount }}</span>
                    </a>
                @endif

                <!-- Desktop Profile -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            @auth
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            @else
                                <svg class="h-5 w-5 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endauth
                        </div>
                    </button>

                    <!-- Desktop Profile Dropdown -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                        @auth
                            <span class="block px-4 py-2 text-sm text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                {{ auth()->user()->name }}
                            </span>
                            <a href="{{ route('account.settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Pengaturan Akun
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('dashboard.home') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Dashboard Admin
                                </a>
                            @elseif(auth()->user()->role !== 'admin')
                                <a href="{{ route('transaction.cart') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Riwayat Pemesanan
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Keluar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Daftar Akun
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
