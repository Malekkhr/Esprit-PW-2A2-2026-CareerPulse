<?php
declare(strict_types=1);

namespace CareerPulse\Models;

use CareerPulse\Database;
use PDOException;

final class User
{
    /** Retourne l'id créé, ou null si l'e-mail existe déjà. */
    public static function create(string $name, string $email, string $password, string $study): ?int
    {
        $pdo = Database::connection();
        try {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, study) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $study]);
            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // e-mail déjà utilisé
                return null;
            }
            throw $e;
        }
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function updateProfile(int $id, array $p): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET name = ?, email = ?, study = ?, skills = ?, goal = ? WHERE id = ?');
        $stmt->execute([$p['name'], $p['email'], $p['study'], $p['skills'], $p['goal'], $id]);
    }

    /** Profil affiché : celui de la session, ou un profil par défaut si non connecté. */
    public static function sessionProfile(): array
    {
        return $_SESSION['profile'] ?? [
            'name' => 'Étudiant CareerPulse',
            'email' => 'etudiant@example.test',
            'study' => 'Technologies Web 2A',
            'skills' => 'PHP, HTML, CSS, communication',
            'goal' => 'Trouver un stage en développement web',
        ];
    }

    public static function all(): array
    {
        return Database::connection()->query('SELECT * FROM users ORDER BY id DESC')->fetchAll();
    }

    public static function toggleStatus(int $id): void
    {
        Database::connection()->prepare("UPDATE users SET status = IF(status = 'active', 'suspended', 'active') WHERE id = ?")->execute([$id]);
    }
}
