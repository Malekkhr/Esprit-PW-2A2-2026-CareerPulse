<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\AuthController;
use CareerPulse\Controllers\DatabaseController;

/**
 * Base de tous les contrôleurs du BACKOFFICE.
 * Le contrôle du rôle « admin » est refait ici à CHAQUE requête (constructeur),
 * avant tout accès à la base : impossible d'atteindre une page admin sans le rôle.
 */
abstract class AdminController extends DatabaseController
{
    public function __construct()
    {
        AuthController::requireRole('admin');
        parent::__construct();
    }
}
