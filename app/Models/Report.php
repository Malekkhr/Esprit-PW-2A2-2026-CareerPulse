<?php
declare(strict_types=1);

namespace CareerPulse\Models;

final class Report extends Model
{
    public static function all(): array
    {
        return self::db()->query('SELECT id, subject, reason, status FROM reports ORDER BY created_at DESC, id')->fetchAll();
    }

    public static function countOpen(): int
    {
        return (int) self::db()->query("SELECT COUNT(*) FROM reports WHERE status = 'Ouvert'")->fetchColumn();
    }

    public static function resolve(string $id): void
    {
        self::db()->prepare("UPDATE reports SET status = 'Traité' WHERE id = ?")->execute([$id]);
    }
}
