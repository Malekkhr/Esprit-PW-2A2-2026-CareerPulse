<?php
declare(strict_types=1);

namespace CareerPulse\Models;

final class Notification extends Model
{
    public static function all(): array
    {
        $rows = self::db()->query('SELECT title, body, is_read, created_at FROM notifications ORDER BY created_at DESC, id DESC')->fetchAll();
        return array_map(static fn (array $n): array => [
            'title' => $n['title'], 'body' => $n['body'],
            'time' => date('d/m/Y', strtotime((string) $n['created_at'])),
            'read' => (bool) $n['is_read'],
        ], $rows);
    }

    public static function markAllRead(): void
    {
        self::db()->exec('UPDATE notifications SET is_read = 1');
    }
}
