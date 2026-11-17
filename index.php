<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$routes = require __DIR__ . '/app/routes.php';

$page = (string) ($_GET['page'] ?? 'home');
if (!isset($routes[$page])) {
    (new CareerPulse\Controllers\ErrorController())->notFound();
    exit;
}

[$controllerClass, $action] = $routes[$page];
try {
    $controller = new $controllerClass();
    $controller->{$action}();
} catch (PDOException $e) {
    http_response_code(500);
    echo '<h1>Base de données indisponible</h1><p>Vérifiez que MySQL est démarré, que la base « careerpulse » est importée (database/careerpulse.sql) et que config/database.php est correct.</p><pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
}
