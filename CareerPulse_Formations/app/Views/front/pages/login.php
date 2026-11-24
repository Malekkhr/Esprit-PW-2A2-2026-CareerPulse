<main class="cp-section">
    <div class="cp-container cp-auth">
        <div class="cp-panel">
            <p class="cp-eyebrow">ESPACE ÉTUDIANT</p><h1>Connexion</h1>
            <?php if (!empty($errors)): ?>
                <div class="cp-alert cp-alert--error cp-form-errors" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="<?= e(route('login')) ?>" id="login-form" data-cp-validate novalidate>
                <?= csrf_input() ?>
                <div class="cp-field"><label for="email">Adresse e-mail</label><input class="cp-input" id="email" name="email" type="email" value="<?= e($old['email'] ?? '') ?>" autocomplete="email" maxlength="150" data-v="required email" required></div>
                <div class="cp-field"><label for="password">Mot de passe</label><input class="cp-input" id="password" name="password" type="password" autocomplete="current-password" maxlength="72" data-v="required" required></div>
                <button class="cp-btn cp-btn--primary cp-btn--block" type="submit">Se connecter</button>
            </form>
            <p class="cp-muted cp-small cp-auth-foot">Pas encore de compte ? <a href="<?= e(route('register')) ?>">Créer un compte</a></p>
        </div>
    </div>
</main>
