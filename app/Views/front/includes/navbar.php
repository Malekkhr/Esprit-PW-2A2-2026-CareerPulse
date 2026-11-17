<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0" aria-label="Navigation principale">
    <a href="<?= e(route('home')) ?>" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <span class="cp-brand-mark" aria-hidden="true">C</span>
        <span class="cp-brand-name">Career<span>Pulse</span></span>
    </a>
    <button class="navbar-toggler me-4" type="button" data-cp-toggle="nav" aria-expanded="false" aria-controls="navbarCollapse" aria-label="Ouvrir le menu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="<?= e(route('home')) ?>" class="nav-item nav-link <?= $currentPage === 'home' ? 'active' : '' ?>">Accueil</a>
            <a href="<?= e(route('offers')) ?>" class="nav-item nav-link <?= $currentPage === 'offers' ? 'active' : '' ?>">Opportunités</a>
            <a href="<?= e(route('courses')) ?>" class="nav-item nav-link <?= $currentPage === 'courses' ? 'active' : '' ?>">Formations</a>
            <a href="<?= e(route('applications')) ?>" class="nav-item nav-link <?= $currentPage === 'applications' ? 'active' : '' ?>">Candidatures</a>
            <a href="<?= e(route('profile')) ?>" class="nav-item nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>">Mon profil</a>
        </div>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="<?= e(route('logout')) ?>" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Déconnexion</a>
        <?php else: ?>
            <a href="<?= e(route('register')) ?>" class="nav-item nav-link <?= $currentPage === 'register' ? 'active' : '' ?>">Inscription</a>
            <a href="<?= e(route('login')) ?>" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Connexion <i class="fa fa-arrow-right ms-3" aria-hidden="true"></i></a>
        <?php endif; ?>
    </div>
</nav>
