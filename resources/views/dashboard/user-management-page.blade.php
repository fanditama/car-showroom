<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Showroom Mobil Admin</title>
    <link rel="icon" href="{{ asset('cars.png') }}">
    <!-- Add FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="flex min-h-screen">
        <!-- sidebar -->
        @include('dashboard.components.sidebar')
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
                <p class="text-gray-600">Kelola user dan role mereka dalam sistem.</p>
            </div>
            <!-- livewire dashboard component -->
            <livewire:dashboard.user-management />
        </div>
    </div>

    <!-- notifikasi -->
    <x-toast-notification />

    <!-- chart js scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @livewireScripts

    <!-- stack untuk script tambahan -->
    @stack('scripts')
</body>

</html>
