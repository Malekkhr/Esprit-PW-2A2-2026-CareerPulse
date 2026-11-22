<?php
declare(strict_types=1);

// NB : session_start() est appelé dans index.php (avec la configuration sécurisée).
require_once __DIR__ . '/helpers.php';

// Chargement automatique : CareerPulse\Models\UserModel => app/Models/UserModel.php, etc.
spl_autoload_register(static function (string $class): void {
    $prefix = 'CareerPulse\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
