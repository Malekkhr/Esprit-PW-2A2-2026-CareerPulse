<header class="sticky top-0 z-40 flex w-full border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-black">
    <div class="flex grow flex-col items-center justify-between lg:flex-row lg:px-6">
        <div class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 dark:border-gray-800 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4">
            <button class="z-50 flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 xl:hidden dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800" type="button" @click.stop="sidebarToggle = !sidebarToggle" aria-label="Ouvrir le menu d’administration"><span class="text-xl">☰</span></button>
            <div class="hidden lg:block"><span class="text-sm text-gray-500 dark:text-gray-400">CareerPulse</span><span class="mx-2 text-gray-300">/</span><span class="text-sm font-semibold text-gray-800 dark:text-white/90"><?= e($title) ?></span></div>
            <div class="flex items-center gap-3">
                <span class="hidden rounded-full bg-warning-50 px-3 py-1 text-xs font-semibold text-warning-700 sm:inline-flex">Données de démonstration</span>
                <a href="<?= e(route('admin/notifications')) ?>" class="relative flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-600 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="Notifications">♢</a>
                <span class="text-right"><strong class="block text-sm text-gray-800 dark:text-white/90">Administrateur</strong><small class="text-xs text-gray-500 dark:text-gray-400">Session locale</small></span>
            </div>
        </div>
    </div>
</header>
