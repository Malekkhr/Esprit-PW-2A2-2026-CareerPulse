<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';

// Chargement automatique : CareerPulse\Models\Offer => app/Models/Offer.php, etc.
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
