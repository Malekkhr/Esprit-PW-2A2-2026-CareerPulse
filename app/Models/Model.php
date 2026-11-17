<?php
declare(strict_types=1);

namespace CareerPulse\Models;

use CareerPulse\Database;
use PDO;

abstract class Model
{
    protected static function db(): PDO
    {
        return Database::connection();
    }
}
