<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Car Showroom') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col items-center">
        @livewire('home.header')
        
        <main class="w-full">
            @yield('content')
        </main>
    </div>
    
    <!-- Toast Notification Component -->
    @if (session()->has('toast_success'))
    <div id="toast-notification" 
         class="fixed bottom-5 right-5 z-50 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 max-w-xs transform transition-all duration-300 ease-in-out translate-y-0 opacity-100">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('toast_success') }}</p>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-hide toast after 4 seconds
        setTimeout(() => {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.classList.add('opacity-0', 'translate-y-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }, 4000);
    </script>
    @endif
    
    @livewireScripts
    
    <!-- Important: Let Livewire load Alpine.js properly -->
</body>
</html>