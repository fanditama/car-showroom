<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Showroom Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Heroicons for validation icons -->
    <link href="https://unpkg.com/heroicons@1.0.6/outline/24/solid.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Buat Akun</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                        <div class="relative">
                            <input type="text" id="name" name="name" value="{{ old('name') }}" autofocus
                                class="w-full px-3 py-2 pr-10 border rounded-md focus:outline-none focus:ring-2 
                                {{ $errors->has('name') 
                                    ? 'border-red-500 focus:ring-red-500 dark:border-red-500' 
                                    : 'border-gray-300 dark:border-gray-700 focus:ring-blue-500' }} 
                                dark:bg-gray-700 dark:text-white">
                            
                            @if($errors->has('name'))
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 pr-10 border rounded-md focus:outline-none focus:ring-2 
                                {{ $errors->has('email') 
                                    ? 'border-red-500 focus:ring-red-500 dark:border-red-500' 
                                    : 'border-gray-300 dark:border-gray-700 focus:ring-blue-500' }} 
                                dark:bg-gray-700 dark:text-white">
                            
                            @if($errors->has('email'))
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-3 py-2 pr-10 border rounded-md focus:outline-none focus:ring-2 
                                {{ $errors->has('password') 
                                    ? 'border-red-500 focus:ring-red-500 dark:border-red-500' 
                                    : 'border-gray-300 dark:border-gray-700 focus:ring-blue-500' }} 
                                dark:bg-gray-700 dark:text-white">
                            
                            @if($errors->has('password'))
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-3 py-2 pr-10 border rounded-md focus:outline-none focus:ring-2 
                                {{ $errors->has('password_confirmation') 
                                    ? 'border-red-500 focus:ring-red-500 dark:border-red-500' 
                                    : 'border-gray-300 dark:border-gray-700 focus:ring-blue-500' }} 
                                dark:bg-gray-700 dark:text-white">
                            
                            @if($errors->has('password_confirmation'))
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col space-y-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                            Daftar
                        </button>

                        <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>