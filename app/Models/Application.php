<?php
declare(strict_types=1);

namespace CareerPulse\Models;

/** Candidature d'un étudiant à une offre. Statuts : À examiner / Acceptée / Refusée. */
final class Application extends Model
{
    /** Retourne true si la candidature est nouvelle, false si elle existait déjà. */
    public static function create(int $userId, string $offerId): bool
    {
        $stmt = self::db()->prepare("INSERT IGNORE INTO applications (user_id, offer_id, status, applied_at) VALUES (?, ?, 'À examiner', CURDATE())");
        $stmt->execute([$userId, $offerId]);
        return $stmt->rowCount() > 0;
    }

    public static function forUser(int $userId): array
    {
        $stmt = self::db()->prepare(
            "SELECT a.offer_id, IF(a.status = 'Soumise', 'À examiner', a.status) AS status, a.applied_at AS date,
                    o.id, o.title, o.company, o.type, o.category, o.location, o.skills, o.match_score AS `match`, o.status AS offer_status
             FROM applications a JOIN offers o ON o.id = a.offer_id
             WHERE a.user_id = ? ORDER BY a.id DESC"
        );
        $stmt->execute([$userId]);

        $rows = [];
        foreach ($stmt->fetchAll() as $r) {
            $rows[] = [
                'offer_id' => $r['offer_id'], 'status' => $r['status'], 'date' => $r['date'],
                'offer' => ['id' => $r['id'], 'title' => $r['title'], 'company' => $r['company'], 'type' => $r['type'],
                            'category' => $r['category'], 'location' => $r['location'], 'skills' => $r['skills'],
                            'match' => $r['match'], 'status' => $r['offer_status']],
            ];
        }
        return $rows;
    }

    /** Toutes les candidatures (back-office) : les « À examiner » d'abord. */
    public static function all(): array
    {
        return self::db()->query(
            "SELECT a.id, IF(a.status = 'Soumise', 'À examiner', a.status) AS status, a.applied_at AS date,
                    u.name AS student_name, u.email AS student_email, u.study AS student_study,
                    o.id AS offer_id, o.title AS offer_title, o.company, o.type
             FROM applications a
             JOIN users u ON u.id = a.user_id
             JOIN offers o ON o.id = a.offer_id
             ORDER BY (IF(a.status = 'Soumise', 'À examiner', a.status) = 'À examiner') DESC, a.id DESC"
        )->fetchAll();
    }

    public static function countPending(): int
    {
        return (int) self::db()->query("SELECT COUNT(*) FROM applications WHERE status IN ('À examiner','Soumise')")->fetchColumn();
    }

    public static function setStatus(int $id, string $status): void
    {
        self::db()->prepare('UPDATE applications SET status = ? WHERE id = ?')->execute([$status, $id]);
    }
}
