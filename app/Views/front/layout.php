<?php
$flashMessage = consume_flash();
?>
<!doctype html>
<html lang="fr">
<head>
    <?php require __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/includes/spinner.php'; ?>
    <?php require __DIR__ . '/includes/navbar.php'; ?>
    <?php if ($flashMessage !== null): ?>
        <div class="container pt-4" role="status">
            <div class="alert <?= $flashMessage['type'] === 'error' ? 'alert-danger' : 'alert-success' ?> mb-0">
                <?= e($flashMessage['message']) ?>
            </div>
        </div>
    <?php endif; ?>
    <?= $content ?>
    <?php require __DIR__ . '/includes/footer.php'; ?>
    <?php require __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
