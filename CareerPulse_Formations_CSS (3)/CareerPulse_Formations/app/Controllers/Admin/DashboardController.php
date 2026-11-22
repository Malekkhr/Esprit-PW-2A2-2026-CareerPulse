<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\CatalogQueries;
use CareerPulse\Models\CourseModel;

final class DashboardController extends AdminController
{
    use CatalogQueries;

    public function index(): void
    {
        $courses = $this->fetchCourses();
        $categories = $this->fetchCategories();
        $publishedCount = count(array_filter($courses, static fn (CourseModel $c): bool => $c->getStatus() === 'Publiée'));

        $this->render('admin', 'dashboard', 'Vue d’ensemble', 'admin/dashboard', [
            'courseCount' => count($courses),
            'publishedCount' => $publishedCount,
            'categoryCount' => count($categories),
            'userCount' => $this->countUsers(),
            'adminCount' => $this->countUsers('admin'),
            'enrollmentCount' => (int) $this->db->query('SELECT COUNT(*) FROM course_enrollments')->fetchColumn(),
            'courses' => array_slice($courses, 0, 5),
        ]);
    }

    private function countUsers(?string $role = null): int
    {
        if ($role === null) {
            return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
        }
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
        $stmt->execute(['role' => $role]);

        return (int) $stmt->fetchColumn();
    }
}
