<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Showroom Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Heroicons for validation icons -->
    <link href="https://unpkg.com/heroicons@1.0.6/outline/24/solid.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Masuk ke Akun</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus
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

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <label for="remember" class="ml-2 text-gray-700 dark:text-gray-300">Ingat saya</label>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                            Masuk
                        </button>
                        
                        <!-- Social Login Divider -->
                        <div class="relative flex items-center py-2">
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                            <span class="flex-shrink mx-4 text-sm text-gray-500 dark:text-gray-400">atau masuk dengan</span>
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        
                        <!-- Social Login Buttons -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Google Login Button -->
                            <a href="{{ route('socialite.redirect', ['provider' => 'google']) }}" 
                               class="flex items-center justify-center px-4 py-2 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-md border border-gray-300 dark:border-gray-600 transition duration-200">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                        <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                                        <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                                        <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                                        <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                                    </g>
                                </svg>
                                Google
                            </a>
                            
                            <!-- Facebook Login Button -->
                            <a href="{{ route('socialite.redirect', ['provider' => 'facebook']) }}" 
                               class="flex items-center justify-center px-4 py-2 bg-[#1877F2] hover:bg-[#166FE5] text-white font-medium rounded-md transition duration-200">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor" d="M20.007 3H3.993A.993.993 0 003 3.993v16.014c0 .548.445.993.993.993h8.621v-6.97H10.27v-2.718h2.346V9.31c0-2.325 1.42-3.591 3.494-3.591.993 0 1.847.074 2.096.107v2.43h-1.438c-1.128 0-1.346.537-1.346 1.324v1.734h2.69l-.35 2.717h-2.34V21h4.587a.993.993 0 00.993-.993V3.993A.993.993 0 0020.007 3z"/>
                                </svg>
                                Facebook
                            </a>
                        </div>

                        <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Toast Notification -->
    @if(session('toast_success'))
    <div id="toast-success" class="fixed bottom-5 right-5 z-50 flex items-center p-4 mb-4 w-full max-w-xs text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal">{{ session('toast_success') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close" onclick="this.parentElement.remove()">
            <span class="sr-only">Close</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <script>
        // Auto-dismiss toast after 4 seconds
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) toast.remove();
        }, 4000);
    </script>
    @endif
</body>
</html>