<?php
$editingUser = $editingUser ?? null;
$isEditing = $editingUser instanceof \CareerPulse\Models\UserModel;
?>
<div class="mb-6"><p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-brand-600 dark:text-brand-400">COMPTES</p><h1 class="text-title-md font-bold text-gray-800 dark:text-white/90">Gestion des utilisateurs</h1><p class="mt-2 text-theme-sm text-gray-500 dark:text-gray-400">Créer, consulter, modifier, suspendre ou supprimer les comptes. Les inscriptions publiques restent limitées au rôle étudiant.</p></div>

<section class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
    <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90"><?= $isEditing ? 'Modifier un compte' : 'Ajouter un utilisateur' ?></h2>
    <form method="post" action="<?= e(route('admin/users')) ?>" data-validate class="grid gap-4 md:grid-cols-2">
        <?= csrf_input() ?>
        <input type="hidden" name="action" value="<?= $isEditing ? 'update' : 'create' ?>">
        <?php if ($isEditing): ?><input type="hidden" name="user_id" value="<?= (int) $editingUser->getId() ?>"><?php endif; ?>
        <label class="text-sm text-gray-700 dark:text-gray-300">Nom d’utilisateur
            <input name="username" required minlength="3" maxlength="50" pattern="[A-Za-z0-9_-]{3,50}" value="<?= e($isEditing ? $editingUser->getUsername() : '') ?>" class="mt-1 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700" autocomplete="username">
        </label>
        <label class="text-sm text-gray-700 dark:text-gray-300">Adresse e-mail
            <input type="email" name="email" required maxlength="150" value="<?= e($isEditing ? $editingUser->getEmail() : '') ?>" class="mt-1 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700" autocomplete="email">
        </label>
        <label class="text-sm text-gray-700 dark:text-gray-300">Formation
            <input name="study" maxlength="160" value="<?= e($isEditing ? $editingUser->getStudy() : '') ?>" class="mt-1 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700">
        </label>
        <label class="text-sm text-gray-700 dark:text-gray-300">Compétences
            <textarea name="skills" maxlength="1000" class="mt-1 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700"><?= e($isEditing ? $editingUser->getSkills() : '') ?></textarea>
        </label>
        <label class="text-sm text-gray-700 dark:text-gray-300">Objectif professionnel
            <textarea name="goal" maxlength="1000" class="mt-1 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700"><?= e($isEditing ? $editingUser->getGoal() : '') ?></textarea>
        </label>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-sm text-gray-700 dark:text-gray-300">Rôle
                <select name="role" required class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                    <option value="user" <?= !$isEditing || $editingUser->getRole() === 'user' ? 'selected' : '' ?>>Étudiant (user)</option>
                    <option value="admin" <?= $isEditing && $editingUser->getRole() === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                </select>
            </label>
            <label class="text-sm text-gray-700 dark:text-gray-300">Statut
                <select name="status" required class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
                    <option value="active" <?= !$isEditing || $editingUser->getStatus() === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="suspended" <?= $isEditing && $editingUser->getStatus() === 'suspended' ? 'selected' : '' ?>>Suspendu</option>
                </select>
            </label>
        </div>
        <label class="text-sm text-gray-700 dark:text-gray-300">Mot de passe <?= $isEditing ? '(laisser vide pour ne pas le changer)' : '' ?>
            <input type="password" name="password" <?= $isEditing ? '' : 'required' ?> minlength="8" maxlength="72" pattern="(?=.*[A-Za-z])(?=.*\d).{8,72}" class="mt-1 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700" autocomplete="new-password">
        </label>
        <label class="text-sm text-gray-700 dark:text-gray-300">Confirmer le mot de passe
            <input type="password" name="password_confirm" <?= $isEditing ? '' : 'required' ?> minlength="8" maxlength="72" class="mt-1 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 dark:border-gray-700" autocomplete="new-password">
        </label>
        <div class="flex items-center gap-3 md:col-span-2">
            <button class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700" type="submit"><?= $isEditing ? 'Enregistrer les changements' : 'Créer le compte' ?></button>
            <?php if ($isEditing): ?><a class="text-sm text-gray-500 hover:text-gray-800" href="<?= e(route('admin/users')) ?>">Annuler</a><?php endif; ?>
        </div>
    </form>
</section>

<section class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]"><h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Comptes enregistrés (<?= count($users) ?>)</h2><div class="overflow-x-auto"><table class="w-full min-w-[900px] text-left text-theme-sm"><thead><tr class="border-y border-gray-100 text-xs uppercase tracking-wider text-gray-400 dark:border-gray-800"><th class="py-3 pr-4">ID</th><th class="py-3 pr-4">Membre</th><th class="py-3 pr-4">Formation</th><th class="py-3 pr-4">Rôle</th><th class="py-3 pr-4">Statut</th><th class="py-3">Actions</th></tr></thead><tbody>
<?php foreach ($users as $account): ?>
<?php $isSelf = $account->getId() === (int) ($_SESSION['user_id'] ?? 0); ?>
<tr class="border-b border-gray-100 last:border-0 dark:border-gray-800"><td class="py-4 pr-4 text-gray-500"><?= (int) $account->getId() ?></td><td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white/90"><?= e($account->getUsername()) ?><small class="mt-1 block font-normal text-gray-400"><?= e($account->getEmail()) ?></small></td><td class="py-4 pr-4 text-gray-600 dark:text-gray-300"><?= e($account->getStudy() !== '' ? $account->getStudy() : '—') ?></td><td class="py-4 pr-4"><?= e($account->getRole() === 'admin' ? 'Administrateur' : 'Étudiant') ?></td><td class="py-4 pr-4"><?= e($account->getStatus() === 'active' ? 'Actif' : 'Suspendu') ?></td><td class="py-4"><div class="flex flex-wrap items-center gap-2">
    <a class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 dark:border-gray-700 dark:text-gray-200" href="<?= e(route('admin/users', ['edit' => $account->getId()])) ?>">Modifier</a>
    <?php if (!$isSelf): ?>
    <form method="post" action="<?= e(route('admin/users')) ?>"><?= csrf_input() ?><input type="hidden" name="action" value="status"><input type="hidden" name="user_id" value="<?= (int) $account->getId() ?>"><input type="hidden" name="status" value="<?= $account->getStatus() === 'active' ? 'suspended' : 'active' ?>"><button class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 dark:border-gray-700 dark:text-gray-200" type="submit"><?= $account->getStatus() === 'active' ? 'Suspendre' : 'Réactiver' ?></button></form>
    <form method="post" action="<?= e(route('admin/users')) ?>" class="js-confirm-delete" data-label="ce compte utilisateur"><?= csrf_input() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="user_id" value="<?= (int) $account->getId() ?>"><button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-700" type="submit">Supprimer</button></form>
    <?php else: ?><span class="text-xs text-gray-400">Votre compte</span><?php endif; ?>
</div></td></tr>
<?php endforeach; ?>
</tbody></table></div></section>
