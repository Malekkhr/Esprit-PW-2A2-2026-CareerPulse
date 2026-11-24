<main class="cp-section">
    <div class="cp-container cp-catalog">
        <div class="cp-page-heading cp-catalog-heading">
            <p class="cp-eyebrow">CATALOGUE &amp; CATÉGORIES</p>
            <h1>Formations disponibles</h1>
            <p class="cp-muted">Filtrez dynamiquement les formations par catégorie, niveau ou mot-clé et inscrivez-vous depuis votre compte.</p>
        </div>

        <aside class="cp-panel cp-catalog-filters" aria-label="Filtres des formations">
            <div class="cp-filters-fields">
                <div class="cp-field">
                    <label for="course-search">Rechercher une formation ou compétence</label>
                    <input type="text" id="course-search" class="cp-input" placeholder="Ex : PHP, SQL, JavaScript, Figma...">
                </div>
                <div class="cp-field">
                    <label for="course-category-filter">Catégorie</label>
                    <select id="course-category-filter" class="cp-input">
                        <option value="">Toutes les catégories (<?= count($courses) ?>)</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e(strtolower($cat->getName())) ?>"><?= e($cat->getName()) ?> (<?= (int) $cat->getCourseCount() ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="cp-field">
                    <label for="course-level-filter">Niveau</label>
                    <select id="course-level-filter" class="cp-input">
                        <option value="">Tous les niveaux</option>
                        <option value="débutant">Débutant</option>
                        <option value="intermédiaire">Intermédiaire</option>
                        <option value="avancé">Avancé</option>
                    </select>
                </div>
            </div>
            <div class="cp-filters-pills" id="category-pills">
                <button type="button" class="cp-btn cp-btn--sm cp-btn--outline cp-pill is-active" data-cat="">Toutes</button>
                <?php foreach ($categories as $cat): ?>
                    <button type="button" class="cp-btn cp-btn--sm cp-btn--outline cp-pill" data-cat="<?= e(strtolower($cat->getName())) ?>"><?= e($cat->getName()) ?></button>
                <?php endforeach; ?>
            </div>
            <p class="cp-filters-counter" id="courses-counter"><?= count($courses) ?> formation(s) affichée(s)</p>
        </aside>

        <section class="cp-catalog-list" aria-label="Liste des formations">
            <div class="cp-grid-courses" id="courses-grid">
                <?php foreach ($courses as $course): ?>
                    <?php $isEnrolled = in_array((int) $course->getId(), $enrolledIds, true); ?>
                    <article class="cp-course-card cp-course-wrapper"
                         data-title="<?= e(strtolower($course->getTitle() . ' ' . $course->getSkills())) ?>"
                         data-category="<?= e(strtolower($course->getCategory())) ?>"
                         data-level="<?= e(strtolower($course->getLevel())) ?>">
                        <div class="cp-course-media">
                            <img src="app/Views/assets/front/img/<?= e($course->getImage()) ?>" alt="Illustration de la formation <?= e($course->getTitle()) ?>">
                            <span class="cp-course-label"><?= e($course->getCategory()) ?></span>
                        </div>
                        <div class="cp-course-body">
                            <h2><?= e($course->getTitle()) ?></h2>
                            <p class="cp-course-skills"><?= e($course->getSkills()) ?></p>
                            <?php if (!empty($course->getDescription())): ?>
                                <p class="cp-muted cp-small"><?= e($course->getDescription()) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="cp-course-action">
                            <form method="post" action="<?= e(route('courses')) ?>">
                                <?= csrf_input() ?>
                                <input type="hidden" name="course_id" value="<?= (int) $course->getId() ?>">
                                <?php if ($isEnrolled): ?>
                                    <input type="hidden" name="action" value="unenroll">
                                    <button type="submit" class="cp-btn cp-btn--outline cp-btn--sm cp-btn--block"><i class="bi bi-check2-circle" aria-hidden="true"></i> Inscrit (Annuler)</button>
                                <?php else: ?>
                                    <input type="hidden" name="action" value="enroll">
                                    <button type="submit" class="cp-btn cp-btn--primary cp-btn--sm cp-btn--block">S’inscrire à cette formation</button>
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="cp-course-meta">
                            <small><i class="fa fa-clock" aria-hidden="true"></i><?= e($course->getDuration()) ?></small>
                            <small><i class="fa fa-signal" aria-hidden="true"></i><?= e($course->getLevel()) ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <div id="no-courses-msg" class="cp-panel cp-empty-state cp-catalog-empty cp-hidden">
            <p>Aucune formation ne correspond à votre recherche.</p>
            <button type="button" id="reset-filters-btn" class="cp-btn cp-btn--outline cp-btn--sm">Réinitialiser les filtres</button>
        </div>
    </div>
</main>
