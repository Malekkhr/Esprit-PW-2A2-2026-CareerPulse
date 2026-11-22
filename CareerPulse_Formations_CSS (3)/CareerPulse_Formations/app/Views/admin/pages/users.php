<div class="mb-6"><p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-brand-600 dark:text-brand-400">COMPTES</p><h1 class="text-title-md font-bold text-gray-800 dark:text-white/90">Utilisateurs</h1><p class="mt-2 text-theme-sm text-gray-500 dark:text-gray-400">Comptes enregistrés dans la base de données. Le rôle (user / admin) est stocké en base ; il se modifie dans phpMyAdmin, jamais depuis un formulaire public.</p></div>
<section class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]"><div class="overflow-x-auto"><table class="w-full min-w-[760px] text-left text-theme-sm"><thead><tr class="border-y border-gray-100 text-xs uppercase tracking-wider text-gray-400 dark:border-gray-800"><th class="py-3 pr-4">ID</th><th class="py-3 pr-4">Membre</th><th class="py-3 pr-4">Formation</th><th class="py-3 pr-4">Rôle</th><th class="py-3 pr-4">Statut</th><th class="py-3">Action</th></tr></thead><tbody>
<?php foreach ($users as $account): ?>
<?php $isSelf = $account->getId() === (int) ($_SESSION['user_id'] ?? 0); ?>
<tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
    <td class="py-4 pr-4 text-gray-500"><?= (int) $account->getId() ?></td>
    <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white/90"><?= e($account->getUsername()) ?><small class="mt-1 block font-normal text-gray-400"><?= e($account->getEmail()) ?></small></td>
    <td class="py-4 pr-4 text-gray-600 dark:text-gray-300"><?= e($account->getStudy() !== '' ? $account->getStudy() : '—') ?></td>
    <td class="py-4 pr-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold <?= $account->getRole() === 'admin' ? 'bg-error-50 text-error-700' : 'bg-brand-50 text-brand-700' ?>"><?= e($account->getRole()) ?></span></td>
    <td class="py-4 pr-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold <?= $account->getStatus() === 'active' ? 'bg-success-50 text-success-700' : 'bg-error-50 text-error-700' ?>"><?= $account->getStatus() === 'active' ? 'Actif' : 'Suspendu' ?></span></td>
    <td class="py-4">
        <?php if ($isSelf): ?>
            <span class="text-xs text-gray-400">Votre compte</span>
        <?php else: ?>
            <form method="post" action="<?= e(route('admin/users')) ?>"><?= csrf_input() ?><input type="hidden" name="user_id" value="<?= (int) $account->getId() ?>"><button class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200" type="submit"><?= $account->getStatus() === 'active' ? 'Suspendre' : 'Réactiver' ?></button></form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody></table></div></section>
