<?php
declare(strict_types=1);

// ============================================================================
// 1. Configuration sécurisée de la session (AVANT session_start)
// ============================================================================
ini_set('session.use_strict_mode', '1');   // refuse un identifiant de session inventé par un attaquant
ini_set('session.cookie_httponly', '1');   // cookie PHPSESSID invisible au JavaScript (anti vol via XSS)
ini_set('session.cookie_samesite', 'Lax'); // limite l'envoi du cookie depuis un site tiers (anti CSRF)
ini_set('session.use_only_cookies', '1');  // l'identifiant de session ne circule jamais dans l'URL
ini_set('session.use_trans_sid', '0');
ini_set('session.cookie_lifetime', '0');   // cookie de session : supprimé à la fermeture du navigateur
ini_set('session.gc_maxlifetime', '1800'); // le serveur garde les sessions 30 min (aligné sur $sessionLifetime)
// Cookie « Secure » activé automatiquement dès que le site est servi en HTTPS (inutile en http://localhost).
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') === '443') {
    ini_set('session.cookie_secure', '1');
}
session_start();

// ============================================================================
// 2. Expiration de la session après 30 minutes d'inactivité
// ============================================================================
$sessionLifetime = 1800;
$sessionExpired = false;
if (isset($_SESSION['last_activity']) && (time() - (int) $_SESSION['last_activity'] > $sessionLifetime)) {
    $sessionExpired = !empty($_SESSION['user_id']);
    $_SESSION = [];
    session_destroy();
    session_start();
}
$_SESSION['last_activity'] = time();

// ============================================================================
// 3. En-têtes HTTP de sécurité
// ============================================================================
$action = isset($_GET['action']) && is_string($_GET['action']) ? $_GET['action'] : 'home';
$isBackoffice = $action === 'admin' || str_starts_with($action, 'admin/');

header('X-Frame-Options: DENY');                                   // anti clickjacking
header('X-Content-Type-Options: nosniff');                         // pas de MIME-sniffing
header('Referrer-Policy: strict-origin-when-cross-origin');
if ($isBackoffice) {
    // Le template d'administration (Alpine.js) nécessite 'unsafe-eval' : limité au backoffice.
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-eval'; "
        . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com data:; "
        . "img-src 'self' data:; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");
} else {
    // Frontoffice : aucun script externe ni inline autorisé (seconde barrière contre le XSS).
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; "
        . "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; "
        . "font-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net data:; "
        . "img-src 'self' data:; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");
}

// ============================================================================
// 4. Chargement de l'application
// ============================================================================
require_once __DIR__ . '/app/bootstrap.php';

if ($sessionExpired) {
    flash('error', 'Votre session a expiré après 30 minutes d’inactivité. Veuillez vous reconnecter.');
}

// ============================================================================
// 5. Routage (point d'entrée unique : index.php?action=...)
// ============================================================================
$routes = require __DIR__ . '/app/routes.php';

if (!isset($routes[$action])) {
    (new CareerPulse\Controllers\ErrorController())->notFound();
    exit;
}

[$controllerClass, $method] = $routes[$action];
try {
    $controller = new $controllerClass();
    $controller->{$method}();
} catch (PDOException $e) {
    error_log('[CareerPulse] ' . $e->getMessage());
    http_response_code(500);
    echo '<h1>Base de données indisponible</h1><p>Vérifiez que MySQL est démarré, que la base « formation » est importée (database/formation.sql) et que config/database.php est correct.</p>';
}
