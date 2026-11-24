<div class="mb-6">
    <p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-brand-600 dark:text-brand-400">TAXONOMIE</p>
    <h1 class="text-title-md font-bold text-gray-800 dark:text-white/90">Gestion des catégories</h1>
    <p class="mt-2 text-theme-sm text-gray-500 dark:text-gray-400">Organisez les familles de formations disponibles sur la plateforme.</p>
</div>

<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    <section class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <h2 class="text-theme-xl font-bold text-gray-800 dark:text-white/90"><?= $editingCategory ? 'Modifier la catégorie' : 'Nouvelle catégorie' ?></h2>
        <form method="post" action="<?= e(route('admin/categories')) ?>" class="mt-4 space-y-4" data-validate>
            <?= csrf_input() ?>
            <input type="hidden" name="action" value="<?= $editingCategory ? 'update' : 'create' ?>">
            <?php if ($editingCategory): ?>
                <input type="hidden" name="category_id" value="<?= (int) $editingCategory->getId() ?>">
            <?php endif; ?>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="name">Nom de la catégorie *</label>
                <input class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="name" name="name" maxlength="100" value="<?= e($editingCategory?->getName() ?? '') ?>" required>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="description">Description</label>
                <textarea class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="description" name="description" maxlength="255" rows="3"><?= e($editingCategory?->getDescription() ?? '') ?></textarea>
            </div>

            <div class="flex gap-2">
                <button class="flex-1 rounded-xl bg-brand-600 px-4 py-2.5 text-theme-sm font-semibold text-white hover:bg-brand-700" type="submit"><?= $editingCategory ? 'Enregistrer' : 'Créer la catégorie' ?></button>
                <?php if ($editingCategory): ?>
                    <a href="<?= e(route('admin/categories')) ?>" class="rounded-xl border border-gray-200 px-4 py-2.5 text-theme-sm font-semibold text-gray-600">Annuler</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white p-5 xl:col-span-2 dark:border-gray-800 dark:bg-white/[0.03]">
        <h2 class="mb-4 text-theme-xl font-bold text-gray-800 dark:text-white/90">Catégories enregistrées (<?= count($categories) ?>)</h2>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-left text-theme-sm">
                <thead>
                    <tr class="border-y border-gray-100 text-xs uppercase tracking-wider text-gray-400 dark:border-gray-800">
                        <th class="py-3 pr-4">Catégorie</th>
                        <th class="py-3 pr-4">Description</th>
                        <th class="py-3 pr-4">Formations</th>
                        <th class="py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                            <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white/90"><?= e($cat->getName()) ?></td>
                            <td class="py-4 pr-4 text-gray-600 dark:text-gray-300"><?= e($cat->getDescription() ?: '—') ?></td>
                            <td class="py-4 pr-4"><span class="rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700 dark:bg-brand-500/15 dark:text-brand-400"><?= (int) $cat->getCourseCount() ?></span></td>
                            <td class="py-4">
                                <div class="flex gap-2">
                                    <a href="<?= e(route('admin/categories', ['edit' => (int) $cat->getId()])) ?>" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200">Modifier</a>
                                    <form method="post" action="<?= e(route('admin/categories')) ?>" class="js-confirm-delete" data-label="<?= e($cat->getName()) ?>">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="category_id" value="<?= (int) $cat->getId() ?>">
                                        <button class="rounded-lg bg-error-50 px-3 py-1.5 text-xs font-semibold text-error-700" type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
