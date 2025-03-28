<div id="toast-notification" 
     class="fixed bottom-5 right-5 z-50 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 max-w-xs transform transition-all duration-300 ease-in-out translate-y-full opacity-0">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-3">
            <p id="toast-message" class="text-sm font-medium text-gray-900 dark:text-white"></p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastMessage = "{{ session('toast_success') }}";
        if (toastMessage) {
            showToast(toastMessage);
        }
    });

    function showToast(message) {
        const toast = document.getElementById('toast-notification');
        const messageElement = document.getElementById('toast-message');
        
        // Set message
        messageElement.textContent = message;
        
        // Show toast
        toast.classList.remove('translate-y-full', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
        
        // Hide toast after 4 seconds
        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-full', 'opacity-0');
        }, 4000);
    }
</script>