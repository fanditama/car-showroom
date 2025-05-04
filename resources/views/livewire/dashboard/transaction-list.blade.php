<div class="p-4">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Transaksi</h2>
            <div class="flex space-x-2">
                <div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder ="Cari Transaksi..."
                        class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <select 
                        wire:model.live="status"
                        class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Pilih Status</option>
                        <option value="pending">Ditunda</option>
                        <option value="processing">Diproses</option>
                        <option value="success">Sukses</option>
                        <option value="cancel">Batal</option>
                        <option value="failed">Gagal</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b cursor-pointer" wire:click="sortBy('order_id')">
                            Order ID
                            @if ($sortField === 'order_id')
                                @if ($sortDirection === 'asc')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </th>
                        <th class="py-2 px-4 border-b">Pelanggan</th>
                        <th class="py-2 px-4 border-b">Mobil</th>
                        <th class="py-2 px-4 border-b cursor-pointer" wire:click="sortBy('transaction_date')">
                            Tanggal
                            @if ($sortField === 'transaction_date')
                                @if ($sortDirection === 'asc')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </th>
                        <th class="py-2 px-4 border-b cursor-pointer" wire:click="sortBy('total_amount')">
                            Jumlah
                            @if ($sortField === 'total_amount')
                                @if ($sortDirection === 'asc')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </th>
                        <th class="py-2 px-4 border-b">Metode Pembayaran</th>
                        <th class="py-2 px-4 border-b cursor-pointer" wire:click="sortBy('status')">
                            Status
                            @if ($sortField === 'status')
                                @if ($sortDirection === 'asc')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </th>
                        <th class="py-2 px-4 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ $transaction->order_id ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b">
                                <div>{{ $transaction->user->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-gray-500">{{ $transaction->user->email ?? '' }}</div>
                            </td>
                            <td class="py-2 px-4 border-b">
                                @if($transaction->car)
                                    <div>{{ $transaction->car->brand }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->car->model }}</div>
                                @else
                                    <span class="text-gray-500">Mobil tidak ditemukan</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b">{{ $transaction->transaction_date ? $transaction->transaction_date->format('d M Y, H:i') : 'N/A' }}</td>
                            <td class="py-2 px-4 border-b font-medium">{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 border-b">{{ $transaction->payment_method ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $transaction->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $transaction->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->status === 'cancel' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $transaction->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <button
                                    wire:click="viewTransaction({{ $transaction->id }})"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm"
                                >
                                    Tampilan Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 px-4 text-center text-gray-500">Transaksi tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- pop-up modal transaksi -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Detail Transaksi
                    </h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                @if($selectedTransaction)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Informasi Order</h4>
                                <div class="mt-2 border rounded-md p-4">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <p class="text-sm text-gray-500">Order ID</p>
                                            <p class="font-medium">{{ $selectedTransaction->order_id ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Tanggal Transaksi</p>
                                            <p class="font-medium">
                                                {{ $selectedTransaction->transaction_date ? $selectedTransaction->transaction_date->format('d M Y, H:i') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Status</p>
                                            <p>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $transaction->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $transaction->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $transaction->status === 'cancel' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $transaction->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                                ">
                                                    {{ ucfirst($selectedTransaction->status) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Metode Pembayaran</p>
                                            <p class="font-medium">{{ $selectedTransaction->payment_method ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Infomasi Pelanggan</h4>
                                <div class="mt-2 border rounded-md p-4">
                                    @if($selectedTransaction->user)
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-sm text-gray-500">Nama</p>
                                                <p class="font-medium">{{ $selectedTransaction->user->name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Email</p>
                                                <p class="font-medium">{{ $selectedTransaction->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-gray-500">Informasi pelanggan tidak tersedia</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Detail Mobil</h4>
                                <div class="mt-2 border rounded-md p-4">
                                    @if($selectedTransaction->car)
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-sm text-gray-500">Nama Mobil</p>
                                                <p class="font-medium">{{ $selectedTransaction->car->brand }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Model</p>
                                                <p class="font-medium">{{ $selectedTransaction->car->model }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-gray-500">Detail mobil tidak tersedia</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Detail Pembayaran</h4>
                                <div class="mt-2 border rounded-md p-4">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <p class="text-sm text-gray-500">Jumlah Total</p>
                                            <p class="font-medium">{{ number_format($selectedTransaction->total_amount, 0, ',', '.') }}</p>
                                        </div>
                                        @if($selectedTransaction->selected_bank)
                                        <div>
                                            <p class="text-sm text-gray-500">Bank Yang Dipilih</p>
                                            <p class="font-medium">{{ $selectedTransaction->selected_bank }}</p>
                                        </div>
                                        @endif
                                        @if($selectedTransaction->payment_date)
                                        <div>
                                            <p class="text-sm text-gray-500">Tanggal Pembayaran</p>
                                            <p class="font-medium">
                                                {{ $selectedTransaction->payment_date ? $selectedTransaction->payment_date->format('d M Y, H:i') : 'N/A' }}
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @if($selectedTransaction->order_address)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Alamat Pengiriman</h4>
                                <div class="mt-2 border rounded-md p-4">
                                    <p>{{ $selectedTransaction->order_address }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t pt-4">
                        <div class="flex justify-end">
                            @if($selectedTransaction->payment_url)
                            <a href="{{ $selectedTransaction->payment_url }}" target="_blank" class="bg-green-500 text-white px-4 py-2 rounded mr-2 hover:bg-green-600">
                                Link Pembayaran
                            </a>
                            @endif
                            <button wire:click="closeModal" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                Tutup
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-500">Detail transaksi tidak tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
