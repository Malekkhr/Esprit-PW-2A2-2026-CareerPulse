<main class="container py-5">
    <div class="cp-page-heading mb-4"><p class="cp-eyebrow">OPPORTUNITÉS</p><h1>Stages et emplois</h1><p class="text-muted">Des exemples de démonstration à filtrer et à ajouter à votre suivi.</p></div>
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a class="btn <?= $type === '' ? 'btn-primary' : 'btn-outline-primary' ?>" href="<?= e(route('offers')) ?>">Tout voir</a>
        <a class="btn <?= $type === 'Stage' ? 'btn-primary' : 'btn-outline-primary' ?>" href="<?= e(route('offers', ['type' => 'Stage'])) ?>">Stages</a>
        <a class="btn <?= $type === 'Emploi' ? 'btn-primary' : 'btn-outline-primary' ?>" href="<?= e(route('offers', ['type' => 'Emploi'])) ?>">Emplois</a>
    </div>
    <div class="row g-4">
        <?php foreach ($offers as $offer): ?>
            <div class="col-lg-6">
                <article class="cp-opportunity-card h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3"><span class="cp-type-tag"><?= e($offer['type']) ?></span><span class="cp-match-pill"><?= e($offer['match']) ?>% match</span></div>
                    <p class="cp-company mt-4 mb-1"><?= e($offer['company']) ?> · <?= e($offer['location']) ?></p>
                    <h2><?= e($offer['title']) ?></h2>
                    <p class="cp-muted"><?= e($offer['category']) ?> · Compétences : <?= e($offer['skills']) ?></p>
                    <form method="post" action="<?= e(route('offers')) ?>">
                        <?= csrf_input() ?>
                        <input type="hidden" name="offer_id" value="<?= e($offer['id']) ?>">
                        <button class="btn btn-primary" type="submit">Ajouter à mes candidatures <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i></button>
                    </form>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
    <p class="small text-muted mt-4">Les organisations, offres et scores affichés ici sont fictifs. Ils ne correspondent pas à des annonces réelles.</p>
</main>
