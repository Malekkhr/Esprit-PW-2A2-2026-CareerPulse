<main class="container py-5">
    <div class="cp-page-heading mb-4"><p class="cp-eyebrow">ESPACE ÉTUDIANT</p><h1>Mon profil</h1><p class="text-muted">Un profil de démonstration pour montrer les informations utilisées par le parcours CareerPulse.</p></div>
    <div class="row g-4">
        <div class="col-lg-8"><form class="cp-form-panel" method="post" action="<?= e(route('profile')) ?>">
            <?= csrf_input() ?>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label" for="name">Nom affiché</label><input class="form-control" id="name" name="name" value="<?= e($profile['name']) ?>" required></div>
                <div class="col-md-6"><label class="form-label" for="email">Adresse e-mail</label><input class="form-control" id="email" name="email" type="email" value="<?= e($profile['email']) ?>" required></div>
                <div class="col-md-6"><label class="form-label" for="study">Formation</label><input class="form-control" id="study" name="study" value="<?= e($profile['study']) ?>"></div>
                <div class="col-12"><label class="form-label" for="skills">Compétences</label><textarea class="form-control" id="skills" name="skills" rows="3"><?= e($profile['skills']) ?></textarea><div class="form-text">Séparez les compétences par des virgules.</div></div>
                <div class="col-12"><label class="form-label" for="goal">Objectif professionnel</label><textarea class="form-control" id="goal" name="goal" rows="3"><?= e($profile['goal']) ?></textarea></div>
                <div class="col-12"><button class="btn btn-primary" type="submit">Enregistrer le profil</button></div>
            </div>
        </form></div>
        <div class="col-lg-4"><aside class="cp-side-panel"><span class="cp-feature-icon"><i class="bi bi-file-earmark-person" aria-hidden="true"></i></span><h2 class="h4 mt-3">CV numérique</h2><p class="text-muted">La maquette décrit l’import et la synchronisation du CV. Le prototype ne stocke pas de fichier.</p><span class="cp-status-label">À raccorder</span></aside><aside class="cp-side-panel mt-4"><p class="cp-eyebrow">MODE DÉMONSTRATION</p><p class="mb-0">Connecté, vos modifications de profil sont enregistrées dans la base de données.</p></aside></div>
    </div>
</main>
