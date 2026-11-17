<main class="container py-5">
    <div class="cp-page-heading mb-4"><p class="cp-eyebrow">SUIVI</p><h1>Mes candidatures</h1><p class="text-muted">Vos candidatures enregistrées apparaissent ici.</p></div>
    <?php if ($applications === []): ?>
        <div class="cp-empty-state"><span class="cp-feature-icon"><i class="bi bi-send" aria-hidden="true"></i></span><h2 class="h4 mt-3">Pas encore de candidature</h2><p class="text-muted">Parcourez les opportunités de démonstration et ajoutez-en une à votre suivi.</p><a class="btn btn-primary" href="<?= e(route('offers')) ?>">Parcourir les offres</a></div>
    <?php else: ?>
        <div class="table-responsive cp-table-wrap"><table class="table align-middle mb-0"><thead><tr><th>Opportunité</th><th>Organisation</th><th>Date</th><th>Statut</th></tr></thead><tbody>
        <?php foreach ($applications as $application): ?>
            <tr><td><strong><?= e($application['offer']['title']) ?></strong><div class="small text-muted"><?= e($application['offer']['type']) ?></div></td><td><?= e($application['offer']['company']) ?></td><td><?= e($application['date']) ?></td><td><span class="cp-status-label"><?= e($application['status']) ?></span></td></tr>
        <?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</main>
