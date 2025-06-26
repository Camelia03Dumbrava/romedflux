<!-- toast.php -->
<div id="toast-container" class="fixed top-20 right-6 z-50 space-y-3"></div>

<script>
    function showToast(message, type = 'success') {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
        };

        const bgColors = {
            success: 'bg-green-100 border-green-200 text-green-800',
            error: 'bg-red-100 border-red-200 text-red-800',
            info: 'bg-blue-100 border-blue-200 text-blue-800',
        };

        const iconClass = icons[type] || icons.info;
        const colorClasses = bgColors[type] || bgColors.info;

        const toast = document.createElement('div');
        toast.className = `toast relative flex items-center space-x-3 px-6 py-3 rounded-lg border shadow-lg ${colorClasses} transition-opacity duration-500 opacity-100`;

        toast.innerHTML = `
    <i class="fas ${iconClass} text-lg"></i>
    <span class="flex-1">${message}</span>
    <button class="ml-auto pl-4 text-xl font-bold text-gray-400 hover:text-gray-700 focus:outline-none transition" onclick="this.parentElement.remove()">
        &times;
    </button>
`;

        const container = document.getElementById('toast-container');
        container.appendChild(toast);

        // Auto-dismiss
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 10000);
    }

    // Toast din PHP (flash via sesiune)
    window.addEventListener("DOMContentLoaded", () => {
        <?php if (isset($_SESSION['toast'])):
            $msg = json_encode($_SESSION['toast']['message']);
            $type = json_encode($_SESSION['toast']['type']);
            unset($_SESSION['toast']); ?>
            showToast(<?= $msg ?>, <?= $type ?>);
        <?php endif; ?>
    });
</script>