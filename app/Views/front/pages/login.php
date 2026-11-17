<main class="container py-5">
    <div class="row justify-content-center"><div class="col-lg-5">
        <div class="cp-form-panel">
            <p class="cp-eyebrow">ESPACE ÉTUDIANT</p><h1 class="h2">Connexion</h1>
            <form method="post" action="<?= e(route('login')) ?>" class="mt-4">
                <?= csrf_input() ?>
                <div class="mb-3"><label class="form-label" for="email">Adresse e-mail</label><input class="form-control" id="email" name="email" type="email" required></div>
                <div class="mb-3"><label class="form-label" for="password">Mot de passe</label><input class="form-control" id="password" name="password" type="password" required></div>
                <button class="btn btn-primary w-100" type="submit">Se connecter</button>
            </form>
            <p class="small text-muted mt-3 mb-0">Pas encore de compte ? <a href="<?= e(route('register')) ?>">Créer un compte</a></p>
        </div>
    </div></div>
</main>
