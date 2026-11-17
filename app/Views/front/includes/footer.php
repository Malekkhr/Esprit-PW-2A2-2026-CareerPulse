<footer class="container-fluid bg-dark text-light footer pt-5 mt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-5 col-md-6">
                <a href="<?= e(route('home')) ?>" class="footer-brand">CareerPulse</a>
                <p class="mt-3 mb-0">Un parcours étudiant qui relie profil, formations et opportunités professionnelles.</p>
                <p class="small text-white-50 mt-3 mb-0">Prototype académique · données fictives de démonstration</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-3">Explorer</h5>
                <a class="btn btn-link" href="<?= e(route('offers')) ?>">Opportunités</a>
                <a class="btn btn-link" href="<?= e(route('courses')) ?>">Formations</a>
                <a class="btn btn-link" href="<?= e(route('applications')) ?>">Candidatures</a>
            </div>
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white mb-3">Équipe MindPrint</h5>
                <p class="mb-3">Projet Technologies Web 2A · 2026–2027</p>
                <a class="btn btn-link" href="<?= e(route('admin/dashboard')) ?>">Ouvrir le back-office de démonstration</a>
                <p class="small text-white-50 mt-3 mb-0">Modèle eLearning par HTML Codex, distribué via ThemeWagon (CC BY 4.0).</p>
            </div>
        </div>
    </div>
    <div class="container border-top border-secondary py-3 small text-white-50">CareerPulse · Prototype pédagogique · Le back-office de démonstration n’est pas protégé par authentification.</div>
</footer>
