<?php
$current_page = basename($_SERVER['PHP_SELF']);
function is_active($page)
{
    global $current_page;
    return $current_page === $page
        ? 'text-blue-700 font-semibold border-b-2 border-blue-600'
        : 'text-gray-700 hover:text-blue-600';
}
?>
<head>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <img src="https://ai-public.creatie.ai/gen_page/logo_placeholder.png"
                     alt="ROMed Flux Logo" class="h-12 w-auto" />
                <span class="ml-3 text-xl font-semibold text-gray-900">ROMed Flux</span>
            </div>
            <nav class="hidden md:flex space-x-6">
                <a href="dashboard.php"
                   class="<?= is_active('dashboard.php') ?> px-3 py-2 text-sm font-medium">
                   Panou principal
                </a>
                <a href="bo1.php"
                   class="<?= is_active('bo1.php') ?> px-3 py-2 text-sm font-medium">
                   BO1
                </a>
                <a href="bo2.php"
                   class="<?= is_active('bo2.php') ?> px-3 py-2 text-sm font-medium">
                   BO2
                </a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
                    <a href="admin.php"
                       class="<?= is_active('admin.php') ?> px-3 py-2 text-sm font-medium">
                       Administrare
                    </a>
                <?php endif; ?>
            </nav>
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open"
                        class="flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none">
                    <i class="fas fa-user-circle text-2xl mr-2"></i>
                    <span><?= htmlspecialchars(getDisplayName($_SESSION)) ?></span>
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50"
                     style="display: none;">
                    <a href="logout.php"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>
