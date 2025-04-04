<div id="toast-notification"
     class="fixed bottom-5 right-5 z-50 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 min-w-[300px] max-w-xs transform transition-all duration-300 ease-in-out translate-y-full opacity-0">
    <div class="flex items-center space-x-4">
        <div class="flex-shrink-0" id="toast-icon">
            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="flex-1 overflow-hidden">
            <span id="toast-message" class="inline-flex text-sm font-medium text-gray-900 dark:text-white break-words"></span>
        </div>
        <div class="flex-shrink-0 ml-2">
            <button type="button"
                    class="text-gray-400 hover:text-gray-500"
                    id="close-toast"
                    title="Tutup notifikasi"
                    aria-label="Tutup notifikasi">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    // event handle
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (eventParams) => {
            if (eventParams && eventParams[0]) {
                showToast(eventParams[0].message, eventParams[0].type);
            } else {
                showToast('Notification', 'info');
            }
        });
    });

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast-notification');
        const messageElement = document.getElementById('toast-message');
        const iconElement = document.getElementById('toast-icon');

        // atur pesan
        messageElement.innerHTML = message;

        // atur icon berdasarkan tipe notifikasi
        switch(type) {
            case 'info':
                iconElement.innerHTML = `<svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`;
                break;
            case 'error':
                iconElement.innerHTML = `<svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`;
                break;
            default:
                iconElement.innerHTML = `<svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`;
        }

        // tampilkan toast
        toast.classList.remove('translate-y-full', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        // sembunyikan setelah 4 detik
        setTimeout(hideToast, 4000);
    }

    function hideToast() {
        const toast = document.getElementById('toast-notification');
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-full', 'opacity-0');
    }

    // atur tombol tutup
    document.getElementById('close-toast').addEventListener('click', hideToast);
</script>
