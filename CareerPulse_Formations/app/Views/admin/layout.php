<?php
$flashMessage = consume_flash();
?>
<!doctype html>
<html lang="fr">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body x-data="{ page: 'career-pulse', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? false; $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" :class="{'dark bg-gray-900': darkMode === true}">
    <?php require __DIR__ . '/partials/preloader.html'; ?>
    <div class="flex h-screen overflow-hidden">
        <?php require __DIR__ . '/partials/sidebar.php'; ?>
        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
            <?php require __DIR__ . '/partials/header.php'; ?>
            <main id="main-content" class="mx-auto w-full max-w-(--breakpoint-2xl) p-4 pb-20 md:p-6 md:pb-8">
                <?php if ($flashMessage !== null): ?>
                    <div class="mb-5 rounded-xl border px-4 py-3 text-theme-sm <?= $flashMessage['type'] === 'error' ? 'border-error-200 bg-error-50 text-error-700' : 'border-success-200 bg-success-50 text-success-700' ?>" role="status"><?= e($flashMessage['message']) ?></div>
                <?php endif; ?>
                <?= $content ?>
            </main>
            <?php require __DIR__ . '/partials/footer.html'; ?>
        </div>
    </div>
    <?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
