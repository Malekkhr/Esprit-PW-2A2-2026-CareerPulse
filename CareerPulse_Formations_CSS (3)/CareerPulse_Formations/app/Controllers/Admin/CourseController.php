<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\CatalogQueries;

final class CourseController extends AdminController
{
    use CatalogQueries;

    private const ALLOWED_IMAGES = ['course-1.png', 'course-2.png', 'course-3.png'];
    private const ALLOWED_LEVELS = ['Débutant', 'Intermédiaire', 'Avancé'];
    private const ALLOWED_STATUS = ['Publiée', 'Brouillon'];

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $action = (string) ($_POST['action'] ?? 'create');

            if ($action === 'delete') {
                $id = (int) ($_POST['course_id'] ?? 0);
                if ($id > 0) {
                    $this->deleteCourse($id);
                    flash('success', 'La formation a été supprimée.');
                }
                redirect_to('admin/courses');
            }

            $title = trim((string) ($_POST['title'] ?? ''));
            $categoryId = (int) ($_POST['category_id'] ?? 0);
            $duration = trim((string) ($_POST['duration'] ?? ''));
            $level = (string) ($_POST['level'] ?? 'Débutant');
            $image = (string) ($_POST['image'] ?? 'course-1.png');
            $skills = trim((string) ($_POST['skills'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            $status = (string) ($_POST['status'] ?? 'Publiée');

            $category = $categoryId > 0 ? $this->findCategory($categoryId) : null;

            if ($title === '' || $category === null || $duration === '') {
                flash('error', 'Veuillez renseigner le titre, la catégorie et la durée de la formation.');
                redirect_to('admin/courses');
            }
            if (mb_strlen($title) > 200 || mb_strlen($duration) > 60 || mb_strlen($skills) > 255) {
                flash('error', 'Titre (200), durée (60) ou compétences (255) : longueur maximale dépassée.');
                redirect_to('admin/courses');
            }

            $payload = [
                'category_id' => $category->getId(),
                'title' => $title,
                'category' => $category->getName(),
                'duration' => $duration,
                'level' => in_array($level, self::ALLOWED_LEVELS, true) ? $level : 'Débutant',
                'image' => in_array($image, self::ALLOWED_IMAGES, true) ? $image : 'course-1.png',
                'skills' => $skills,
                'description' => $description,
                'status' => in_array($status, self::ALLOWED_STATUS, true) ? $status : 'Publiée',
            ];

            if ($action === 'update') {
                $id = (int) ($_POST['course_id'] ?? 0);
                if ($id > 0 && $this->findCourse($id) !== null) {
                    $this->updateCourse($id, $payload);
                    flash('success', 'La formation a été mise à jour.');
                }
            } else {
                $this->createCourse($payload);
                flash('success', 'La nouvelle formation a été ajoutée au catalogue.');
            }
            redirect_to('admin/courses');
        }

        $editId = (int) ($_GET['edit'] ?? 0);

        $this->render('admin', 'courses', 'Gestion des formations', 'admin/courses', [
            'courses' => $this->fetchCourses(),
            'categories' => $this->fetchCategories(),
            'editingCourse' => $editId > 0 ? $this->findCourse($editId) : null,
        ]);
    }

    private function createCourse(array $d): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO courses (category_id, title, category, duration, level, image, skills, description, status)
             VALUES (:category_id, :title, :category, :duration, :level, :image, :skills, :description, :status)'
        );
        $stmt->execute($d);
    }

    private function updateCourse(int $id, array $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE courses
             SET category_id = :category_id, title = :title, category = :category, duration = :duration,
                 level = :level, image = :image, skills = :skills, description = :description, status = :status
             WHERE id = :id'
        );
        $stmt->execute($d + ['id' => $id]);
    }

    private function deleteCourse(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
