<main class="container py-5">
    <div class="cp-page-heading mb-5"><p class="cp-eyebrow">FORMATION</p><h1>Développer vos compétences</h1><p class="text-muted">Parcourez des exemples de formations liés aux familles de compétences présentées dans le projet.</p></div>
    <div class="row g-4">
        <?php foreach ($courses as $index => $course): ?>
            <div class="col-md-6 col-xl-4">
                <article class="course-item bg-white h-100 cp-course-card">
                    <div class="position-relative overflow-hidden"><img class="img-fluid" src="app/Views/assets/front/img/<?= e($course['image']) ?>" alt="Illustration de la formation <?= e($course['title']) ?>"><span class="cp-course-label"><?= e($course['category']) ?></span></div>
                    <div class="text-center p-4 pb-0"><h2 class="mb-3 h4"><?= e($course['title']) ?></h2><p class="text-muted"><?= e($course['skills']) ?></p></div>
                    <div class="d-flex border-top mt-4"><small class="flex-fill text-center border-end py-2"><i class="fa fa-clock text-primary me-2" aria-hidden="true"></i><?= e($course['duration']) ?></small><small class="flex-fill text-center py-2"><i class="fa fa-signal text-primary me-2" aria-hidden="true"></i><?= e($course['level']) ?></small></div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="cp-note mt-5"><i class="bi bi-info-circle me-2" aria-hidden="true"></i>Les formations affichées sont des exemples fictifs. Aucun fournisseur de formation n’est connecté.</div>
</main>
