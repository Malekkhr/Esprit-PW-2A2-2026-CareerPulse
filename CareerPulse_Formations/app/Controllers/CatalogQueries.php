<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\CategoryModel;
use CareerPulse\Models\CourseModel;

/**
 * Requêtes SQL préparées de lecture sur les catégories, formations et inscriptions,
 * partagées entre les contrôleurs. Nécessite la propriété $this->db (PDO).
 */
trait CatalogQueries
{
    /** Colonnes communes des requêtes sur les formations (pas de constante dans un trait : compatibilité PHP < 8.2). */
    private function courseColumns(): string
    {
        return 'co.id, co.category_id, co.title, COALESCE(ca.name, co.category) AS category,
                co.duration, co.level, co.image, co.skills, co.description, co.status, co.created_at';
    }

    /** @return CategoryModel[] */
    private function fetchCategories(): array
    {
        $sql = 'SELECT c.id, c.name, c.description, c.created_at, COUNT(co.id) AS course_count
                FROM categories c
                LEFT JOIN courses co ON co.category_id = c.id
                GROUP BY c.id, c.name, c.description, c.created_at
                ORDER BY c.name ASC';

        return array_map(static fn (array $row): CategoryModel => CategoryModel::fromArray($row), $this->db->query($sql)->fetchAll());
    }

    private function findCategory(int $id): ?CategoryModel
    {
        $stmt = $this->db->prepare('SELECT id, name, description, created_at FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? CategoryModel::fromArray($row) : null;
    }

    /** @return CourseModel[] */
    private function fetchCourses(bool $publishedOnly = false): array
    {
        $sql = 'SELECT ' . $this->courseColumns() . ' FROM courses co LEFT JOIN categories ca ON ca.id = co.category_id'
            . ($publishedOnly ? " WHERE co.status = 'Publiée'" : '')
            . ' ORDER BY co.id DESC';

        return array_map(static fn (array $row): CourseModel => CourseModel::fromArray($row), $this->db->query($sql)->fetchAll());
    }

    private function findCourse(int $id): ?CourseModel
    {
        $sql = 'SELECT ' . $this->courseColumns() . ' FROM courses co LEFT JOIN categories ca ON ca.id = co.category_id WHERE co.id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? CourseModel::fromArray($row) : null;
    }

    /** @return int[] identifiants des formations auxquelles l'utilisateur est inscrit */
    private function fetchEnrolledIds(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT course_id FROM course_enrollments WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);

        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    /** @return CourseModel[] */
    private function fetchEnrolledCourses(int $userId): array
    {
        $sql = 'SELECT ' . $this->courseColumns() . ', ce.enrolled_at
                FROM course_enrollments ce
                INNER JOIN courses co ON co.id = ce.course_id
                LEFT JOIN categories ca ON ca.id = co.category_id
                WHERE ce.user_id = :user_id
                ORDER BY ce.enrolled_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return array_map(static fn (array $row): CourseModel => CourseModel::fromArray($row), $stmt->fetchAll());
    }
}
