<main>
    <section class="cp-hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <p class="cp-eyebrow">ORIENTATION · FORMATION · INSERTION</p>
                    <h1>Donnez un cap à votre carrière</h1>
                    <p class="cp-hero-copy">CareerPulse réunit votre profil, les formations utiles et les opportunités qui peuvent faire avancer votre parcours.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="<?= e(route('offers')) ?>" class="btn btn-primary btn-lg">Explorer les opportunités <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i></a>
                        <a href="<?= e(route('profile')) ?>" class="btn btn-light btn-lg">Compléter mon profil</a>
                    </div>
                    <div class="cp-proof-row mt-5">
                        <span><i class="bi bi-person-check" aria-hidden="true"></i> Profil étudiant</span>
                        <span><i class="bi bi-stars" aria-hidden="true"></i> Matching ciblé</span>
                        <span><i class="bi bi-bell" aria-hidden="true"></i> Suivi des candidatures</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="cp-hero-visual">
                        <img src="app/Views/assets/front/img/carousel-1.jpg" alt="Étudiants en formation" class="cp-hero-image">
                        <div class="cp-match-card"><span class="cp-match-dot"></span><div><strong>Votre prochain pas</strong><small>Une opportunité adaptée à vos compétences</small></div><span class="cp-match-score">92%</span></div>
                        <div class="cp-hero-caption"><span class="cp-pulse-line" aria-hidden="true">⌁</span> Votre parcours, en mouvement</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-4">
            <div class="col-md-4"><div class="cp-feature"><span class="cp-feature-icon"><i class="bi bi-person-vcard" aria-hidden="true"></i></span><h2>Un profil qui vous suit</h2><p>Rassemblez votre parcours, vos compétences et vos objectifs dans un espace unique.</p></div></div>
            <div class="col-md-4"><div class="cp-feature"><span class="cp-feature-icon"><i class="bi bi-compass" aria-hidden="true"></i></span><h2>Des pistes plus pertinentes</h2><p>Parcourez des cours, stages et emplois selon vos préférences et votre profil.</p></div></div>
            <div class="col-md-4"><div class="cp-feature"><span class="cp-feature-icon"><i class="bi bi-activity" aria-hidden="true"></i></span><h2>Un parcours suivi</h2><p>Retrouvez vos candidatures et leurs statuts dans une vue claire.</p></div></div>
        </div>
    </section>

    <section class="cp-section-soft py-5">
        <div class="container py-lg-4">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
                <div><p class="cp-eyebrow">À DÉCOUVRIR</p><h2 class="cp-section-title">Opportunités suggérées</h2><p class="mb-0 text-muted">Exemples fictifs pour parcourir le prototype.</p></div>
                <a href="<?= e(route('offers')) ?>" class="cp-text-link">Voir toutes les opportunités <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i></a>
            </div>
            <div class="row g-4">
                <?php foreach ($offers as $offer): ?>
                    <div class="col-md-6 col-xl-4">
                        <article class="cp-opportunity-card h-100">
                            <div class="d-flex justify-content-between align-items-start"><span class="cp-type-tag"><?= e($offer['type']) ?></span><span class="cp-match-pill"><?= e($offer['match']) ?>% match</span></div>
                            <p class="cp-company mt-4 mb-1"><?= e($offer['company']) ?> · <?= e($offer['location']) ?></p>
                            <h3><?= e($offer['title']) ?></h3>
                            <p class="cp-muted mb-4"><?= e($offer['skills']) ?></p>
                            <a class="cp-text-link" href="<?= e(route('offers')) ?>">Voir le détail <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i></a>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6"><img src="app/Views/assets/front/img/about.jpg" alt="Étudiante travaillant sur un ordinateur" class="img-fluid cp-about-image"></div>
            <div class="col-lg-6"><p class="cp-eyebrow">UNE BOUCLE D’APPRENTISSAGE</p><h2 class="cp-section-title">La bonne formation peut ouvrir la prochaine porte</h2><p class="text-muted">CareerPulse relie les opportunités aux compétences que vous souhaitez développer. Si un parcours demande une compétence à renforcer, la plateforme propose de chercher une formation correspondante.</p><a class="btn btn-primary mt-2" href="<?= e(route('courses')) ?>">Explorer les formations <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i></a></div>
        </div>
    </section>

    <section class="cp-cta-band"><div class="container d-flex justify-content-between align-items-center flex-wrap gap-3"><div><p class="cp-eyebrow mb-2">VOTRE PROCHAIN PAS</p><h2 class="mb-0">Commencez par votre profil étudiant</h2></div><a class="btn btn-light btn-lg" href="<?= e(route('profile')) ?>">Mettre à jour mon profil</a></div></section>
</main>
