<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan</title>
    <link rel="icon" href="{{ asset('cars.png') }}">

    <!-- Tailwind CSS -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <!-- Tambahkan CSS khusus untuk peta -->
    <style>
        #map {
            height: 400px !important;
            width: 100% !important;
            min-height: 400px !important;
            z-index: 1 !important;
            position: relative !important;
        }

        .leaflet-container {
            height: 100% !important;
            width: 100% !important;
        }

        /* Highlight textarea alamat setelah diupdate */
        textarea[wire\:model="address"]:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        /* Animasi untuk perubahan alamat */
        @keyframes highlight {
            0% { background-color: rgba(59, 130, 246, 0.1); }
            100% { background-color: transparent; }
        }

        .address-updated {
            animation: highlight 2s ease-out;
        }

        /* cegah livewire update mengubah tampilan map */
        [wire\:loading], [wire\:loading\.delay], [wire\:loading\.inline-block], [wire\:loading\.inline], [wire\:loading\.block], [wire\:loading\.flex], [wire\:loading\.table], [wire\:loading\.grid], [wire\:loading\.inline-flex] {
            display: none !important;
        }

        /* Mencegah elemen map dari flickering selama update */
        div[wire\:ignore] {
            display: block !important;
        }

        /* Mengatasi masalah re-rendering */
        #map .leaflet-pane,
        #map .leaflet-control,
        #map .leaflet-top,
        #map .leaflet-bottom {
            z-index: 1 !important;
            position: absolute !important;
        }
        
    </style>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ config('midtrans.client_key') }}"></script>
    @livewireStyles

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin="">
    </script>
</head>
<body class="bg-gray-100">
    <livewire:order.order-form :car="$car" />

    @livewireScripts
    @stack('scripts')
</body>
</html>
