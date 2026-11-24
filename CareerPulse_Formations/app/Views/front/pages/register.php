<main class="cp-section">
    <div class="cp-container cp-auth cp-auth--wide">
        <div class="cp-panel">
            <p class="cp-eyebrow">INSCRIPTION</p><h1>Créer un compte CareerPulse</h1>
            <?php if (!empty($errors)): ?>
                <div class="cp-alert cp-alert--error cp-form-errors" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <!-- novalidate : confort de saisie en JavaScript (app.js) ; la validation de référence est faite côté serveur (AuthController::register) -->
            <form method="post" action="<?= e(route('register')) ?>" id="register-form" novalidate>
                <?= csrf_input() ?>
                <div class="cp-field"><label for="username">Nom d’utilisateur</label><input class="cp-input" id="username" name="username" value="<?= e($old['username'] ?? '') ?>" autocomplete="username"><span class="cp-help">3 à 50 caractères : lettres, chiffres, _ ou -</span><span class="cp-error" data-error-for="username" aria-live="polite"></span></div>
                <div class="cp-field"><label for="email">Adresse e-mail</label><input class="cp-input" id="email" name="email" type="text" inputmode="email" value="<?= e($old['email'] ?? '') ?>" autocomplete="email"><span class="cp-error" data-error-for="email" aria-live="polite"></span></div>
                <div class="cp-field"><label for="study">Formation actuelle (facultatif)</label><input class="cp-input" id="study" name="study" value="<?= e($old['study'] ?? '') ?>"><span class="cp-error" data-error-for="study" aria-live="polite"></span></div>
                <div class="cp-field">
                    <label for="password">Mot de passe</label>
                    <input class="cp-input" id="password" name="password" type="password" autocomplete="new-password">
                    <ul class="cp-rules" id="password-rules">
                        <li data-rule="length">Au moins 8 caractères</li>
                        <li data-rule="letter">Au moins une lettre</li>
                        <li data-rule="digit">Au moins un chiffre</li>
                    </ul>
                    <span class="cp-error" data-error-for="password" aria-live="polite"></span>
                </div>
                <div class="cp-field"><label for="password_confirm">Confirmer le mot de passe</label><input class="cp-input" id="password_confirm" name="password_confirm" type="password" autocomplete="new-password"><span class="cp-error" data-error-for="password_confirm" aria-live="polite"></span></div>
                <button class="cp-btn cp-btn--primary cp-btn--block" type="submit">S’inscrire</button>
            </form>
            <p class="cp-muted cp-small cp-auth-foot">Déjà inscrit ? <a href="<?= e(route('login')) ?>">Se connecter</a></p>
        </div>
    </div>
</main>
