<?php
/** @var CareerPulse\Models\UserModel $account */
$isSelf = $account->getId() === (int) ($_SESSION['user_id'] ?? 0);
?>
<div class="adm-head">
    <div>
        <p class="adm-eyebrow">COMPTES</p>
        <h1 class="adm-title"><?= e($account->getUsername()) ?></h1>
        <p class="adm-sub">Fiche détaillée de l’utilisateur n° <?= (int) $account->getId() ?>.</p>
    </div>
    <div class="adm-actions">
        <a class="adm-btn" href="<?= e(route('admin/users')) ?>">← Retour à la liste</a>
        <a class="adm-btn adm-btn--primary" href="<?= e(route('admin/users/edit', ['id' => $account->getId()])) ?>">Modifier</a>
    </div>
</div>

<section class="adm-card">
    <h2>Informations</h2>
    <dl class="adm-dl">
        <dt>Nom d’utilisateur</dt><dd><?= e($account->getUsername()) ?><?= $isSelf ? ' (vous)' : '' ?></dd>
        <dt>E-mail</dt><dd><?= e($account->getEmail()) ?></dd>
        <dt>Rôle</dt><dd><span class="adm-badge <?= $account->getRole() === 'admin' ? 'adm-badge--admin' : 'adm-badge--user' ?>"><?= e($account->getRole()) ?></span></dd>
        <dt>Statut</dt><dd><span class="adm-badge <?= $account->getStatus() === 'active' ? 'adm-badge--ok' : 'adm-badge--off' ?>"><?= $account->getStatus() === 'active' ? 'Actif' : 'Suspendu' ?></span></dd>
        <dt>Formation actuelle</dt><dd><?= e($account->getStudy() !== '' ? $account->getStudy() : '—') ?></dd>
        <dt>Compétences</dt><dd><?= e($account->getSkills() !== '' ? $account->getSkills() : '—') ?></dd>
        <dt>Objectif</dt><dd><?= e($account->getGoal() !== '' ? $account->getGoal() : '—') ?></dd>
        <dt>Membre depuis</dt><dd><?= e($account->getCreatedAt()) ?></dd>
    </dl>
</section>

<section class="adm-card">
    <h2>Formations suivies (<?= count($enrolledCourses) ?>)</h2>
    <?php if (empty($enrolledCourses)): ?>
        <p class="adm-empty">Cet utilisateur n’est inscrit à aucune formation.</p>
    <?php else: ?>
        <div class="adm-table-wrap">
            <table class="adm-table" style="min-width: 520px;">
                <thead><tr><th>Formation</th><th>Catégorie</th><th>Durée</th><th>Niveau</th></tr></thead>
                <tbody>
                <?php foreach ($enrolledCourses as $course): ?>
                    <tr>
                        <td class="adm-strong"><?= e($course->getTitle()) ?></td>
                        <td><?= e($course->getCategory()) ?></td>
                        <td><?= e($course->getDuration()) ?></td>
                        <td><?= e($course->getLevel()) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
