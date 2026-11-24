<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

/** Catalogue public des formations + inscription / désinscription (utilisateur connecté). */
final class CourseController extends DatabaseController
{
    use CatalogQueries;

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            if (!AuthController::isAuthenticated()) {
                flash('error', 'Connectez-vous à votre compte pour vous inscrire à une formation.');
                redirect_to('login');
            }
            AuthController::requireAuth(); // revérifie en base que le compte est toujours actif

            $userId = (int) $_SESSION['user_id'];
            $action = (string) ($_POST['action'] ?? 'enroll');
            $courseId = (int) ($_POST['course_id'] ?? 0);

            if ($courseId > 0 && $this->findCourse($courseId) !== null) {
                if ($action === 'unenroll') {
                    $this->unenroll($userId, $courseId);
                    flash('success', 'Votre inscription à cette formation a été annulée.');
                } else {
                    $added = $this->enroll($userId, $courseId);
                    flash('success', $added ? 'Inscription à la formation enregistrée dans votre compte !' : 'Vous êtes déjà inscrit à cette formation.');
                }
            }
            redirect_to('courses');
        }

        $userId = AuthController::isAuthenticated() ? (int) $_SESSION['user_id'] : 0;

        $this->render('front', 'courses', 'Formations & Catégories', 'courses', [
            'courses' => $this->fetchCourses(true),
            'categories' => $this->fetchCategories(),
            'enrolledIds' => $userId > 0 ? $this->fetchEnrolledIds($userId) : [],
        ]);
    }

    private function enroll(int $userId, int $courseId): bool
    {
        $stmt = $this->db->prepare('INSERT IGNORE INTO course_enrollments (user_id, course_id) VALUES (:user_id, :course_id)');
        $stmt->execute(['user_id' => $userId, 'course_id' => $courseId]);

        return $stmt->rowCount() > 0;
    }

    private function unenroll(int $userId, int $courseId): void
    {
        $stmt = $this->db->prepare('DELETE FROM course_enrollments WHERE user_id = :user_id AND course_id = :course_id');
        $stmt->execute(['user_id' => $userId, 'course_id' => $courseId]);
    }
}
