<?php
declare(strict_types=1);

namespace CareerPulse;

use PDO;
use PDOException;

/**
 * Connexion PDO sécurisée, patron de conception Singleton :
 * la connexion n'est créée qu'une seule fois et partagée par toute l'application.
 */
final class Database
{
    private static ?PDO $pdo = null;

    public static function getConnexion(): PDO
    {
        if (self::$pdo === null) {
            $c = require __DIR__ . '/../config/database.php';
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $c['host'], $c['port'], $c['name'], $c['charset']);
            try {
                self::$pdo = new PDO($dsn, $c['user'], $c['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // erreur SQL => exception
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,                   // vraies requêtes préparées (anti injection SQL)
                ]);
            } catch (PDOException $e) {
                // On ne laisse pas fuiter l'identifiant/mot de passe MySQL dans le message d'erreur.
                throw new PDOException('Connexion à la base de données impossible.', (int) $e->getCode());
            }
        }

        return self::$pdo;
    }
}
