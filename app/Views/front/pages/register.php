<main class="container py-5">
    <div class="row justify-content-center"><div class="col-lg-6">
        <div class="cp-form-panel">
            <p class="cp-eyebrow">INSCRIPTION</p><h1 class="h2">Créer un compte CareerPulse</h1>
            <form method="post" action="<?= e(route('register')) ?>" class="mt-4">
                <?= csrf_input() ?>
                <div class="mb-3"><label class="form-label" for="name">Nom complet</label><input class="form-control" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label" for="email">Adresse e-mail</label><input class="form-control" id="email" name="email" type="email" value="<?= e($old['email'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label" for="study">Formation</label><input class="form-control" id="study" name="study" value="<?= e($old['study'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label" for="password">Mot de passe (8 caractères min.)</label><input class="form-control" id="password" name="password" type="password" minlength="8" required></div>
                <div class="mb-3"><label class="form-label" for="password2">Confirmer le mot de passe</label><input class="form-control" id="password2" name="password2" type="password" minlength="8" required></div>
                <button class="btn btn-primary w-100" type="submit">S’inscrire</button>
            </form>
            <p class="small text-muted mt-3 mb-0">Déjà inscrit ? <a href="<?= e(route('login')) ?>">Se connecter</a></p>
        </div>
    </div></div>
</main>
