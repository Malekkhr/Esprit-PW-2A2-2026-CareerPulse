<main class="cp-section">
    <div class="cp-container cp-dashboard">
        <div class="cp-page-heading">
            <p class="cp-eyebrow">ESPACE UTILISATEUR · FRONTOFFICE</p>
            <h1>Bienvenue, <?= e($user->getUsername()) ?></h1>
            <p><span class="cp-badge <?= $user->getRole() === 'admin' ? 'cp-badge--admin' : 'cp-badge--user' ?>">Rôle : <?= e($user->getRole()) ?></span></p>
        </div>

        <section class="cp-panel">
            <h2 class="cp-panel-title">Mes informations</h2>
            <table class="cp-table">
                <tr><th>ID</th><td><?= (int) $user->getId() ?></td></tr>
                <tr><th>Nom d’utilisateur</th><td><?= e($user->getUsername()) ?></td></tr>
                <tr><th>E-mail</th><td><?= e($user->getEmail()) ?></td></tr>
                <tr><th>Formation actuelle</th><td><?= e($user->getStudy() !== '' ? $user->getStudy() : '—') ?></td></tr>
                <tr><th>Membre depuis</th><td><?= e($user->getCreatedAt()) ?></td></tr>
            </table>
            <div class="cp-btn-row cp-mt">
                <a href="<?= e(route('profile')) ?>" class="cp-btn cp-btn--outline cp-btn--sm">Modifier mon profil</a>
                <?php if ($user->getRole() === 'admin'): ?>
                    <a href="<?= e(route('admin')) ?>" class="cp-btn cp-btn--primary cp-btn--sm">Ouvrir le back-office</a>
                <?php endif; ?>
            </div>
        </section>

        <section class="cp-panel">
            <div class="cp-side-head"><h2>Mes formations inscrites</h2><span class="cp-status-label"><?= count($enrolledCourses) ?></span></div>
            <?php if (empty($enrolledCourses)): ?>
                <p class="cp-muted">Vous n’êtes inscrit à aucune formation pour le moment.</p>
                <a href="<?= e(route('courses')) ?>" class="cp-btn cp-btn--primary cp-btn--sm">Parcourir le catalogue</a>
            <?php else: ?>
                <ul class="cp-enrolled">
                    <?php foreach ($enrolledCourses as $course): ?>
                        <li>
                            <div>
                                <strong><?= e($course->getTitle()) ?></strong>
                                <small class="cp-muted"><?= e($course->getCategory()) ?> · <?= e($course->getDuration()) ?> · <?= e($course->getLevel()) ?></small>
                            </div>
                            <span class="cp-type-tag">Inscrit</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <p class="cp-muted cp-small">Ceci est votre espace personnel (frontoffice). Seuls les administrateurs peuvent accéder au back-office.</p>
    </div>
</main>
