<?php /** @var CareerPulse\Models\UserModel[] $users */ ?>
<div class="adm-head">
    <div>
        <p class="adm-eyebrow">COMPTES</p>
        <h1 class="adm-title">Gestion des utilisateurs</h1>
        <p class="adm-sub"><?= count($users) ?> affiché(s) sur <?= (int) $totalUsers ?> compte(s). Ajout, consultation, modification, suppression et gestion des rôles.</p>
    </div>
    <a class="adm-btn adm-btn--primary" href="<?= e(route('admin/users/create')) ?>">+ Ajouter un utilisateur</a>
</div>

<section class="adm-card">
    <form class="adm-toolbar" method="get" action="index.php">
        <input type="hidden" name="action" value="admin/users">
        <input class="adm-input" type="search" name="q" value="<?= e($search) ?>" placeholder="Rechercher (nom, e-mail, formation)…" aria-label="Rechercher un utilisateur">
        <select class="adm-select" name="role" aria-label="Filtrer par rôle">
            <option value="">Tous les rôles</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= e($r) ?>" <?= $roleFilter === $r ? 'selected' : '' ?>><?= e($r) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="adm-btn" type="submit">Filtrer</button>
        <?php if ($search !== '' || $roleFilter !== ''): ?>
            <a class="adm-btn" href="<?= e(route('admin/users')) ?>">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr><th>ID</th><th>Membre</th><th>Formation</th><th>Rôle</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="6" class="adm-empty">Aucun utilisateur ne correspond à la recherche.</td></tr>
            <?php endif; ?>
            <?php foreach ($users as $account): ?>
                <?php $isSelf = $account->getId() === (int) ($_SESSION['user_id'] ?? 0); ?>
                <tr>
                    <td><?= (int) $account->getId() ?></td>
                    <td>
                        <span class="adm-strong"><?= e($account->getUsername()) ?></span><?= $isSelf ? ' <span class="adm-badge adm-badge--user">vous</span>' : '' ?>
                        <small><?= e($account->getEmail()) ?></small>
                    </td>
                    <td><?= e($account->getStudy() !== '' ? $account->getStudy() : '—') ?></td>
                    <td>
                        <?php if ($isSelf): ?>
                            <span class="adm-badge <?= $account->getRole() === 'admin' ? 'adm-badge--admin' : 'adm-badge--user' ?>"><?= e($account->getRole()) ?></span>
                        <?php else: ?>
                            <form class="adm-inline" method="post" action="<?= e(route('admin/users/role')) ?>">
                                <?= csrf_input() ?>
                                <input type="hidden" name="user_id" value="<?= (int) $account->getId() ?>">
                                <select class="adm-select adm-select--sm" name="role" aria-label="Rôle de <?= e($account->getUsername()) ?>">
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= e($r) ?>" <?= $account->getRole() === $r ? 'selected' : '' ?>><?= e($r) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="adm-btn adm-btn--sm" type="submit">OK</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td><span class="adm-badge <?= $account->getStatus() === 'active' ? 'adm-badge--ok' : 'adm-badge--off' ?>"><?= $account->getStatus() === 'active' ? 'Actif' : 'Suspendu' ?></span></td>
                    <td>
                        <div class="adm-actions">
                            <a class="adm-btn adm-btn--sm" href="<?= e(route('admin/users/show', ['id' => $account->getId()])) ?>">Voir</a>
                            <a class="adm-btn adm-btn--sm" href="<?= e(route('admin/users/edit', ['id' => $account->getId()])) ?>">Modifier</a>
                            <?php if (!$isSelf): ?>
                                <form method="post" action="<?= e(route('admin/users/status')) ?>">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="user_id" value="<?= (int) $account->getId() ?>">
                                    <button class="adm-btn adm-btn--sm" type="submit"><?= $account->getStatus() === 'active' ? 'Suspendre' : 'Réactiver' ?></button>
                                </form>
                                <form method="post" action="<?= e(route('admin/users/delete')) ?>" class="js-confirm-delete" data-label="<?= e($account->getUsername()) ?>">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="user_id" value="<?= (int) $account->getId() ?>">
                                    <button class="adm-btn adm-btn--sm adm-btn--danger" type="submit">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
