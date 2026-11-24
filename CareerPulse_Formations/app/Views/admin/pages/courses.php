<?php
// Valeurs du formulaire : saisie précédente (après une erreur de validation) sinon formation en cours de modification.
$f = [
    'title'       => old_input('title', $editingCourse?->getTitle() ?? ''),
    'category_id' => (int) old_input('category_id', (string) ($editingCourse?->getCategoryId() ?? 0)),
    'duration'    => old_input('duration', $editingCourse?->getDuration() ?? ''),
    'level'       => old_input('level', $editingCourse?->getLevel() ?? 'Débutant'),
    'image'       => old_input('image', $editingCourse?->getImage() ?? 'course-1.png'),
    'skills'      => old_input('skills', $editingCourse?->getSkills() ?? ''),
    'description' => old_input('description', $editingCourse?->getDescription() ?? ''),
    'status'      => old_input('status', $editingCourse?->getStatus() ?? 'Publiée'),
];
?>
<div class="mb-6">
    <p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-brand-600 dark:text-brand-400">CATALOGUE PÉDAGOGIQUE</p>
    <h1 class="text-title-md font-bold text-gray-800 dark:text-white/90">Gestion des formations</h1>
    <p class="mt-2 text-theme-sm text-gray-500 dark:text-gray-400">Créez, modifiez ou supprimez les formations associées aux catégories.</p>
</div>

<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    <section class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <h2 class="text-theme-xl font-bold text-gray-800 dark:text-white/90"><?= $editingCourse ? 'Modifier la formation' : 'Ajouter une formation' ?></h2>
        <form method="post" action="<?= e(route('admin/courses')) ?>" class="mt-4 space-y-4" id="course-form" data-cp-validate novalidate>
            <?= csrf_input() ?>
            <input type="hidden" name="action" value="<?= $editingCourse ? 'update' : 'create' ?>">
            <?php if ($editingCourse): ?>
                <input type="hidden" name="course_id" value="<?= (int) $editingCourse->getId() ?>">
            <?php endif; ?>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="title">Titre de la formation *</label>
                <input class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="title" name="title" value="<?= e($f['title']) ?>" maxlength="200" data-label="Le titre" data-v="required max" data-max="200" required>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="category_id">Catégorie *</label>
                <select class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="category_id" name="category_id" data-label="La catégorie" data-v="required" required>
                    <option value="">-- Choisir une catégorie --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int) $cat->getId() ?>" <?= ($f['category_id'] === (int) $cat->getId()) ? 'selected' : '' ?>><?= e($cat->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="duration">Durée *</label>
                    <input class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="duration" name="duration" placeholder="Ex : 4 semaines" value="<?= e($f['duration']) ?>" maxlength="60" data-label="La durée" data-v="required max pattern" data-max="60" data-pattern="^\d{1,3}\s?(heures?|h|jours?|j|semaines?|mois)$" data-pattern-message="Durée invalide : un nombre et une unité (ex : 4 semaines, 40 heures, 2 mois)." required>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="level">Niveau</label>
                    <select class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="level" name="level">
                        <?php foreach (['Débutant', 'Intermédiaire', 'Avancé'] as $lvl): ?>
                            <option value="<?= e($lvl) ?>" <?= ($f['level'] === $lvl) ? 'selected' : '' ?>><?= e($lvl) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="image">Illustration</label>
                    <select class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="image" name="image">
                        <option value="course-1.png" <?= ($f['image'] === 'course-1.png') ? 'selected' : '' ?>>Visuel Développement</option>
                        <option value="course-2.png" <?= ($f['image'] === 'course-2.png') ? 'selected' : '' ?>>Visuel Data</option>
                        <option value="course-3.png" <?= ($f['image'] === 'course-3.png') ? 'selected' : '' ?>>Visuel Design</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="status">Statut</label>
                    <select class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="status" name="status">
                        <option value="Publiée" <?= ($f['status'] === 'Publiée') ? 'selected' : '' ?>>Publiée</option>
                        <option value="Brouillon" <?= ($f['status'] === 'Brouillon') ? 'selected' : '' ?>>Brouillon</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="skills">Compétences clés</label>
                <input class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="skills" name="skills" placeholder="Ex : JS · DOM · Events" value="<?= e($f['skills']) ?>" maxlength="255" data-label="Les compétences" data-v="max" data-max="255">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500" for="description">Description</label>
                <textarea class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" id="description" name="description" rows="3" maxlength="2000" data-label="La description" data-v="max" data-max="2000"><?= e($f['description']) ?></textarea>
            </div>

            <div class="flex gap-2">
                <button class="flex-1 rounded-xl bg-brand-600 px-4 py-2.5 text-theme-sm font-semibold text-white hover:bg-brand-700" type="submit"><?= $editingCourse ? 'Enregistrer' : 'Ajouter la formation' ?></button>
                <?php if ($editingCourse): ?>
                    <a href="<?= e(route('admin/courses')) ?>" class="rounded-xl border border-gray-200 px-4 py-2.5 text-theme-sm font-semibold text-gray-600">Annuler</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white p-5 xl:col-span-2 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-theme-xl font-bold text-gray-800 dark:text-white/90">Liste des formations (<?= count($courses) ?>)</h2>
            <input type="text" id="admin-course-search" placeholder="Filtrer la liste..." class="rounded-xl border border-gray-200 px-3.5 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-theme-sm" id="admin-courses-table">
                <thead>
                    <tr class="border-y border-gray-100 text-xs uppercase tracking-wider text-gray-400 dark:border-gray-800">
                        <th class="py-3 pr-4">Formation</th>
                        <th class="py-3 pr-4">Catégorie</th>
                        <th class="py-3 pr-4">Durée / Niveau</th>
                        <th class="py-3 pr-4">Statut</th>
                        <th class="py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                            <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white/90">
                                <?= e($course->getTitle()) ?>
                                <small class="mt-1 block font-normal text-gray-400"><?= e($course->getSkills()) ?></small>
                            </td>
                            <td class="py-4 pr-4"><span class="rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700 dark:bg-brand-500/15 dark:text-brand-400"><?= e($course->getCategory()) ?></span></td>
                            <td class="py-4 pr-4 text-gray-600 dark:text-gray-300"><?= e($course->getDuration()) ?> · <?= e($course->getLevel()) ?></td>
                            <td class="py-4 pr-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold <?= $course->getStatus() === 'Publiée' ? 'bg-success-50 text-success-700' : 'bg-warning-50 text-warning-700' ?>"><?= e($course->getStatus()) ?></span></td>
                            <td class="py-4">
                                <div class="flex gap-2">
                                    <a href="<?= e(route('admin/courses', ['edit' => (int) $course->getId()])) ?>" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200">Modifier</a>
                                    <form method="post" action="<?= e(route('admin/courses')) ?>" class="js-confirm-delete" data-label="<?= e($course->getTitle()) ?>">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="course_id" value="<?= (int) $course->getId() ?>">
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
