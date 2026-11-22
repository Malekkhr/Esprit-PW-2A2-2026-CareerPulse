<?php
$flashMessage = consume_flash();
?>
<!doctype html>
<html lang="fr">
<head>
    <?php require __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/includes/spinner.html'; ?>
    <?php require __DIR__ . '/includes/navbar.php'; ?>
    <?php if ($flashMessage !== null): ?>
        <div class="cp-container cp-flash" role="status">
            <div class="cp-alert <?= $flashMessage['type'] === 'error' ? 'cp-alert--error' : 'cp-alert--success' ?>">
                <?= e($flashMessage['message']) ?>
            </div>
        </div>
    <?php endif; ?>
    <?= $content ?>
    <?php require __DIR__ . '/includes/footer.php'; ?>
    <?php require __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
