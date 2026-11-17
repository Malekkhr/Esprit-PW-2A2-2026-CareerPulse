<div x-cloak x-show="sidebarToggle" class="fixed inset-0 z-99 bg-gray-900/50 backdrop-blur-xs xl:hidden" aria-hidden="true" @click="sidebarToggle = false" x-transition.opacity></div>
<aside x-cloak :class="sidebarToggle ? 'translate-x-0 xl:w-22.5' : '-translate-x-full rtl:translate-x-full'" class="sidebar fixed top-0 left-0 z-9999 flex h-screen w-72.5 flex-col border-r border-gray-200 bg-white px-5 transition-all duration-300 xl:static xl:translate-x-0 dark:border-gray-800 dark:bg-black" @click.outside="if (window.innerWidth < 1280) sidebarToggle = false" aria-label="Navigation d’administration">
    <div class="flex items-center justify-between gap-2 py-7">
        <a href="<?= e(route('admin/dashboard')) ?>" class="flex items-center gap-3" aria-label="CareerPulse, tableau de bord">
            <span class="admin-brand-mark">C</span>
            <span class="admin-brand-text" :class="sidebarToggle ? 'xl:hidden' : ''">Career<span>Pulse</span></span>
        </a>
        <button class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 xl:hidden dark:hover:bg-gray-800" type="button" @click="sidebarToggle = false" aria-label="Fermer le menu">×</button>
    </div>
    <div class="no-scrollbar flex flex-1 flex-col overflow-y-auto pb-6">
        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-widest text-gray-400">Administration</p>
        <nav class="flex flex-col gap-1" aria-label="Menu du back-office">
            <?php
            $adminLinks = [
                ['admin/dashboard', 'Vue d’ensemble', 'M5'],
                ['admin/offers', 'Modération des offres', 'M2'],
                ['admin/applications', 'Candidatures', 'M3'],
                ['admin/users', 'Utilisateurs', 'M1'],
                ['admin/reports', 'Signalements', 'M5'],
                ['admin/notifications', 'Notifications', 'M6'],
            ];
            foreach ($adminLinks as [$page, $label, $code]):
                $active = $currentPage === $page;
            ?>
                <a href="<?= e(route($page)) ?>" class="group flex items-center gap-3 rounded-xl px-3 py-3 text-theme-sm font-medium transition-colors <?= $active ? 'menu-item-active bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400' : 'menu-item-inactive text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' ?>" <?= $active ? 'aria-current="page"' : '' ?>><span class="admin-nav-code"><?= e($code) ?></span><span :class="sidebarToggle ? 'xl:hidden' : ''"><?= e($label) ?></span></a>
            <?php endforeach; ?>
        </nav>
        <div class="mt-auto rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-semibold uppercase tracking-wider text-brand-700 dark:text-brand-400">Mode démonstration</p>
            <p class="mt-2 text-xs leading-5 text-gray-500 dark:text-gray-400">Les données viennent de la base MySQL. Aucune authentification admin n’est configurée.</p>
            <a class="mt-3 inline-flex text-xs font-semibold text-brand-600 hover:text-brand-800 dark:text-brand-400" href="<?= e(route('home')) ?>">Retour au Front Office →</a>
        </div>
    </div>
</aside>
