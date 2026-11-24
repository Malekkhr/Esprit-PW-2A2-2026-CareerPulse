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
    /** Durée : nombre + unité (ex : « 4 semaines », « 40 heures », « 2 mois »). */
    private const DURATION_PATTERN = '/^\d{1,3}\s?(heures?|h|jours?|j|semaines?|mois)$/iu';
    private const MAX_DESCRIPTION = 2000;

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

            $errors = $this->validateCourse($title, $category !== null, $duration, $skills, $description);
            if ($errors !== []) {
                // On garde la saisie : l'administrateur corrige au lieu de tout retaper.
                flash('error', implode(' ', $errors));
                remember_input([
                    'title' => $title, 'category_id' => $categoryId, 'duration' => $duration, 'level' => $level,
                    'image' => $image, 'skills' => $skills, 'description' => $description, 'status' => $status,
                ]);
                $editId = (int) ($_POST['course_id'] ?? 0);
                redirect_to('admin/courses', $action === 'update' && $editId > 0 ? ['edit' => $editId] : []);
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

    /**
     * Validation serveur d'une formation (même règles que le JavaScript de admin-formations.js).
     *
     * @return string[]
     */
    private function validateCourse(string $title, bool $categoryExists, string $duration, string $skills, string $description): array
    {
        $errors = [];
        if ($title === '') {
            $errors[] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($title) > 200) {
            $errors[] = 'Le titre ne doit pas dépasser 200 caractères.';
        }
        if (!$categoryExists) {
            $errors[] = 'Choisissez une catégorie valide.';
        }
        if ($duration === '') {
            $errors[] = 'La durée est obligatoire.';
        } elseif (mb_strlen($duration) > 60 || !preg_match(self::DURATION_PATTERN, $duration)) {
            $errors[] = 'Durée invalide : indiquez un nombre et une unité (ex : 4 semaines, 40 heures, 2 mois).';
        }
        if (mb_strlen($skills) > 255) {
            $errors[] = 'Les compétences ne doivent pas dépasser 255 caractères.';
        }
        if (mb_strlen($description) > self::MAX_DESCRIPTION) {
            $errors[] = 'La description ne doit pas dépasser ' . self::MAX_DESCRIPTION . ' caractères.';
        }

        return $errors;
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
