<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-brand-600 dark:text-brand-400">PILOTAGE PÉDAGOGIQUE</p>
        <h1 class="text-title-md font-bold text-gray-800 dark:text-white/90">Vue d’ensemble</h1>
        <p class="mt-2 text-theme-sm text-gray-500 dark:text-gray-400">Gestion des formations, des catégories et des comptes étudiants.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="<?= e(route('admin/categories')) ?>" class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-theme-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-200">+ Nouvelle catégorie</a>
        <a href="<?= e(route('admin/courses')) ?>" class="inline-flex items-center rounded-xl bg-brand-600 px-4 py-3 text-theme-sm font-semibold text-white hover:bg-brand-700">+ Nouvelle formation <span class="ml-2">→</span></a>
    </div>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <?php
    $metrics = [
        ['Formations au catalogue', $courseCount, $publishedCount . ' publiée(s)'],
        ['Catégories actives', $categoryCount, 'Domaines'],
        ['Inscriptions formations', $enrollmentCount, 'Parcours'],
        ['Comptes utilisateurs', $userCount, $adminCount . ' admin(s)'],
    ];
    foreach ($metrics as [$label, $value, $tag]):
    ?>
        <article class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400"><?= e($label) ?></p>
                    <p class="mt-3 text-title-md font-bold text-gray-800 dark:text-white/90"><?= e($value) ?></p>
                </div>
                <span class="rounded-lg bg-brand-50 px-2.5 py-1.5 text-xs font-bold text-brand-700 dark:bg-brand-500/15 dark:text-brand-400"><?= e($tag) ?></span>
            </div>
            <p class="mt-4 text-xs text-gray-400">Base de données formation</p>
        </article>
    <?php endforeach; ?>
</div>

<div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
    <section class="rounded-2xl border border-gray-200 bg-white p-5 xl:col-span-2 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-theme-xl font-bold text-gray-800 dark:text-white/90">Dernières formations</h2>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Catalogue géré depuis le back-office.</p>
            </div>
            <a class="text-theme-sm font-semibold text-brand-600 dark:text-brand-400" href="<?= e(route('admin/courses')) ?>">Gérer tout →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-theme-sm">
                <thead>
                    <tr class="border-y border-gray-100 text-xs uppercase tracking-wider text-gray-400 dark:border-gray-800">
                        <th class="py-3 pr-4">Formation</th>
                        <th class="py-3 pr-4">Catégorie</th>
                        <th class="py-3 pr-4">Niveau</th>
                        <th class="py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                            <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white/90"><?= e($course->getTitle()) ?><small class="mt-1 block font-normal text-gray-400"><?= e($course->getDuration()) ?></small></td>
                            <td class="py-4 pr-4 text-gray-600 dark:text-gray-300"><?= e($course->getCategory()) ?></td>
                            <td class="py-4 pr-4 text-gray-600 dark:text-gray-300"><?= e($course->getLevel()) ?></td>
                            <td class="py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold <?= $course->getStatus() === 'Publiée' ? 'bg-success-50 text-success-700' : 'bg-warning-50 text-warning-700' ?>"><?= e($course->getStatus()) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <aside class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <h2 class="text-theme-xl font-bold text-gray-800 dark:text-white/90">Accès rapide</h2>
        <p class="mt-2 text-theme-sm leading-6 text-gray-500 dark:text-gray-400">Administrez l’ensemble du module pédagogique et les comptes inscrits.</p>
        <div class="mt-5 space-y-3">
            <a class="admin-quick-link" href="<?= e(route('admin/courses')) ?>"><span>01</span> Ajouter / modifier une formation</a>
            <a class="admin-quick-link" href="<?= e(route('admin/categories')) ?>"><span>02</span> Gérer les catégories</a>
            <a class="admin-quick-link" href="<?= e(route('admin/users')) ?>"><span>03</span> Gérer les comptes utilisateurs</a>
        </div>
    </aside>
</div>
