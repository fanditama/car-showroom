# Showroom Mobil

[![Laravel CI](https://github.com/fanditama/mobile-showroom/actions/workflows/laravel.yml/badge.svg)](https://github.com/fanditama/mobile-showroom/actions/workflows/laravel.yml)

Aplikasi web modern untuk showroom mobil yang dibangun dengan Laravel dan Livewire. Aplikasi ini memungkinkan pengguna untuk menjelajahi, memfilter, dan membeli mobil secara online dengan integrasi peta lokasi dan pembayaran online.

## Fitur

- **Penjelajahan Mobil**: Lihat semua mobil yang tersedia dengan informasi detail
- **Pemfilteran**: Filter mobil berdasarkan jenis dan urutkan berdasarkan harga (terendah/tertinggi)
- **Autentikasi Pengguna**: Daftar, masuk, dan kelola akun Anda (termasuk login dengan media sosial)
- **Keranjang Belanja**: Tambahkan mobil ke keranjang dan kelola pilihan Anda
- **Lokasi Pengiriman**: Pilih lokasi pengiriman dengan peta interaktif
- **Pembayaran Online**: Proses pembayaran dengan gateway pembayaran Midtrans
- **Desain Responsif**: Berfungsi dengan baik di perangkat desktop maupun mobile

## Teknologi yang Digunakan

- **Laravel**: Framework backend
- **Livewire**: Untuk komponen dinamis dan reaktif tanpa menulis JavaScript
- **Tailwind CSS**: Untuk styling
- **Laravel Socialite**: Untuk autentikasi media sosial
- **OpenStreetMap & Leaflet.js**: Untuk integrasi peta dan pemilihan lokasi
- **Midtrans**: Untuk integrasi pembayaran online

## Memulai

### Prasyarat

- PHP 8.0 atau lebih tinggi
- Composer
- Node.js dan NPM
- MySQL atau database lain yang didukung oleh Laravel
- Akun Midtrans untuk integrasi pembayaran
- Akun developer untuk OAuth (jika menggunakan login sosial)

### Instalasi

1. Clone repositori:
   ```bash
   git clone https://github.com/username-anda/car-showroom.git
2. Masuk ke direktori proyek:
   ```bash
   cd car-showroom
3. Instal dependensi PHP:
   ```bash
   composer install
4. Instal dependensi JavaScript:
   ```bash
   npm install
5. Buat salinan file .env.example:
   ```bash
   cp .env.example .env
6. Generate kunci aplikasi:
   ```bash
   php artisan key:generate
7. Konfigurasi database Anda di file .env.
8. Jalankan migrasi:
   ```bash
   php artisan migrate
9. Isi database dengan data awal:
   ```bash
   php artisan db:seed
10. Build aset:
    ```bash
    npm run dev
11. Mulai server pengembangan:
    ```bash
    php artisan serve

### Konfigurasi Integrasi
### Integrasi Socialite (Login Media Sosial)
1. Daftar aplikasi di platform yang ingin Anda integrasikasikan (Google, Facebook)
2. Tambahkan kredensial di file .env:
    - GOOGLE_CLIENT_ID=your-client-id
    - GOOGLE_CLIENT_SECRET=your-client-secret
    - GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
    - FACEBOOK_CLIENT_ID=your-client-id
    - FACEBOOK_CLIENT_SECRET=your-client-secret
    - FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
3. Konfigurasi provider di config/services.php

### Integrasi OpenStreetMap
OpenStreetMap digunakan untuk memilih lokasi pengiriman. Integrasi ini menggunakan Leaflet.js dan tidak memerlukan API key.
1. Pastikan file JavaScript Leaflet.js dimuat di halaman:
   ```
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
   ```

2. Inisialisasi peta di halaman formulir pemesanan:
   ```
   var map = L.map('map').setView([-6.200000, 106.816666], 13);
   L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
   }).addTo(map);
   ```

Integrasi Midtrans
1. Daftar akun di Midtrans
2. Dapatkan kunci API (Client Key dan Server Key)
3. Tambahkan kredensial di file .env:
   MIDTRANS_SERVER_KEY=your-server-key
   MIDTRANS_CLIENT_KEY=your-client-key
   MIDTRANS_IS_PRODUCTION=false
   MIDTRANS_MERCHANT_ID=your-merchant-id
4. Tambahkan Snap.js di halaman checkout:
   ```
   <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
   ```
5. Implementasikan callback untuk menangani respons      pembayaran

### Pengujian
Aplikasi ini mencakup pengujian komprehensif untuk komponen Livewire-nya. Jalankan pengujian dengan:

php artisan test

### Rangkaian pengujian utama meliputi:
- Pengujian komponen Home/Content
- Pengujian komponen Home/Header
- Pengujian fungsionalitas keranjang
- Pengujian autentikasi pengguna
- Pengujian integrasi peta
- Pengujian pembayaran
### Struktur Proyek
Proyek ini mengikuti struktur standar Laravel dengan organisasi tambahan untuk komponen Livewire:
- app/Livewire/Home/ - Berisi komponen Livewire untuk halaman beranda
- app/Livewire/Order/ - Berisi komponen untuk pemesanan dan pembayaran
- app/Models/ - Berisi model seperti Car, Cart, dan User
- app/Services/ - Berisi layanan integrasi seperti MidtransService
- tests/Feature/Livewire/ - Berisi pengujian untuk komponen Livewire
