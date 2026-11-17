<?php
declare(strict_types=1);

namespace CareerPulse\Models;

final class Offer extends Model
{
    private const COLUMNS = 'id, title, company, type, category, location, skills, match_score AS `match`, status';

    public static function all(): array
    {
        return self::db()->query('SELECT ' . self::COLUMNS . ' FROM offers ORDER BY id')->fetchAll();
    }

    /** Offres visibles par les étudiants : validées, de type Stage ou Emploi. */
    public static function visibleToStudents(string $type = ''): array
    {
        $sql = 'SELECT ' . self::COLUMNS . " FROM offers WHERE status = 'Validée' AND type IN ('Stage','Emploi')";
        $params = [];
        if ($type !== '') {
            $sql .= ' AND type = ?';
            $params[] = $type;
        }
        $stmt = self::db()->prepare($sql . ' ORDER BY id');
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Retourne l'offre si un étudiant peut y postuler, sinon null. */
    public static function findApplicable(string $id): ?array
    {
        $stmt = self::db()->prepare('SELECT ' . self::COLUMNS . " FROM offers WHERE id = ? AND status = 'Validée' AND type IN ('Stage','Emploi')");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function exists(string $id): bool
    {
        $stmt = self::db()->prepare('SELECT 1 FROM offers WHERE id = ?');
        $stmt->execute([$id]);
        return (bool) $stmt->fetchColumn();
    }

    public static function setStatus(string $id, string $status): void
    {
        self::db()->prepare('UPDATE offers SET status = ? WHERE id = ?')->execute([$status, $id]);
    }
}
