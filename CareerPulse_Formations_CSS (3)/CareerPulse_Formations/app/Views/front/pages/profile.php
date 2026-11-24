<main class="cp-section">
    <div class="cp-container cp-profile">
        <div class="cp-page-heading cp-profile-heading">
            <p class="cp-eyebrow">ESPACE ÉTUDIANT</p>
            <h1>Mon compte &amp; mes formations</h1>
            <p class="cp-muted">Vos informations personnelles et vos inscriptions aux formations sont enregistrées dans la base de données CareerPulse.</p>
        </div>

        <form class="cp-panel cp-profile-form" method="post" action="<?= e(route('profile')) ?>" id="profile-form" data-validate>
            <?= csrf_input() ?>
            <?php if (!empty($errors)): ?>
                <div class="cp-alert cp-alert--error cp-form-errors" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="cp-form-grid">
                <div class="cp-field"><label for="username">Nom d’utilisateur</label><input class="cp-input" id="username" name="username" value="<?= e($user->getUsername()) ?>" required minlength="3" maxlength="50" pattern="[A-Za-z0-9_-]{3,50}"></div>
                <div class="cp-field"><label for="email">Adresse e-mail</label><input class="cp-input" id="email" name="email" type="email" maxlength="150" value="<?= e($user->getEmail()) ?>" required></div>
                <div class="cp-field cp-field--full"><label for="study">Filière / Formation actuelle</label><input class="cp-input" id="study" name="study" maxlength="160" value="<?= e($user->getStudy()) ?>"></div>
                <div class="cp-field cp-field--full"><label for="skills">Compétences</label><textarea class="cp-input" id="skills" name="skills" maxlength="1000" rows="3"><?= e($user->getSkills()) ?></textarea><span class="cp-help">Séparez les compétences par des virgules.</span></div>
                <div class="cp-field cp-field--full"><label for="goal">Objectif d’apprentissage</label><textarea class="cp-input" id="goal" name="goal" maxlength="1000" rows="3"><?= e($user->getGoal()) ?></textarea></div>
                <div class="cp-field--full"><button class="cp-btn cp-btn--primary" type="submit">Enregistrer mon compte</button></div>
            </div>
        </form>

        <aside class="cp-panel cp-profile-side">
            <div class="cp-side-head">
                <h2>Mes formations inscrites</h2>
                <span class="cp-status-label"><?= count($enrolledCourses) ?></span>
            </div>
            <?php if (empty($enrolledCourses)): ?>
                <p class="cp-muted">Vous n’êtes inscrit à aucune formation pour le moment.</p>
                <a href="<?= e(route('courses')) ?>" class="cp-btn cp-btn--primary cp-btn--sm">Parcourir le catalogue</a>
            <?php else: ?>
                <ul class="cp-enrolled">
                    <?php foreach ($enrolledCourses as $ec): ?>
                        <li>
                            <div>
                                <strong><?= e($ec->getTitle()) ?></strong>
                                <small class="cp-muted"><?= e($ec->getCategory()) ?> · <?= e($ec->getDuration()) ?> · <?= e($ec->getLevel()) ?></small>
                            </div>
                            <span class="cp-type-tag">Inscrit</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= e(route('courses')) ?>" class="cp-btn cp-btn--outline cp-btn--sm cp-mt">Gérer mes inscriptions</a>
            <?php endif; ?>
        </aside>
    </div>
</main>
