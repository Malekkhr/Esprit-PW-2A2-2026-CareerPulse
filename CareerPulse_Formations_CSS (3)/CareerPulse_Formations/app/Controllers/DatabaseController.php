<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Database;
use PDO;

/**
 * Contrôleur qui accède à la base : la connexion PDO (Singleton) est
 * initialisée dans le constructeur, comme dans AuthController du workshop.
 */
abstract class DatabaseController extends Controller
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }
}
