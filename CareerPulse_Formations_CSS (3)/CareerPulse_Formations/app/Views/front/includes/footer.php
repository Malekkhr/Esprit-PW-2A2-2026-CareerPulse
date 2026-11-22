<footer class="cp-footer">
    <div class="cp-container cp-footer-grid">
        <div class="cp-footer-brand-col">
            <a href="<?= e(route('home')) ?>" class="cp-footer-brand">CareerPulse</a>
            <p>Un parcours étudiant qui relie compte utilisateur, catégories et catalogue de formations.</p>
            <p class="cp-footer-note">Module Gestion des Formations &amp; Catégories · Base de données formation</p>
        </div>
        <div class="cp-footer-explore">
            <h3>Explorer</h3>
            <div class="cp-footer-links">
                <a href="<?= e(route('home')) ?>">Accueil</a>
                <a href="<?= e(route('courses')) ?>">Formations &amp; Catégories</a>
                <a href="<?= e(route('profile')) ?>">Mon profil</a>
                <a href="<?= e(route('register')) ?>">Inscription</a>
            </div>
        </div>
        <div class="cp-footer-team">
            <h3>Équipe MindPrint</h3>
            <p>Projet Technologies Web 2A · 2026–2027</p>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <div class="cp-footer-links">
                    <a href="<?= e(route('admin')) ?>">Ouvrir le back-office (Formations &amp; Catégories)</a>
                </div>
            <?php endif; ?>
            <p class="cp-footer-note">Modèle eLearning par HTML Codex, distribué via ThemeWagon (CC BY 4.0).</p>
        </div>
    </div>
    <div class="cp-container cp-footer-bottom">CareerPulse · Module Formations, Catégories &amp; Comptes Utilisateurs.</div>
</footer>
