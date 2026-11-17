<?php
declare(strict_types=1);

namespace CareerPulse\Models;

final class Course extends Model
{
    public static function all(): array
    {
        return self::db()->query('SELECT title, category, duration, level, image, skills FROM courses ORDER BY id')->fetchAll();
    }
}
