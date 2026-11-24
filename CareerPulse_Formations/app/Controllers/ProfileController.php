<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use PDOException;

/** Profil de l'utilisateur connecté (frontoffice) : consultation et modification. */
final class ProfileController extends DatabaseController
{
    use UserQueries;
    use CatalogQueries;

    public function index(): void
    {
        AuthController::requireAuth();

        $userId = (int) $_SESSION['user_id'];
        $user = $this->findUserById($userId);
        if ($user === null) {
            (new AuthController())->forceLogout();
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            // On reprend la saisie dans le modèle pour réafficher le formulaire en cas d'erreur.
            $user->setUsername(trim((string) ($_POST['username'] ?? '')));
            $user->setEmail(strtolower(trim((string) ($_POST['email'] ?? ''))));
            $user->setStudy(trim((string) ($_POST['study'] ?? '')));
            $user->setSkills(trim((string) ($_POST['skills'] ?? '')));
            $user->setGoal(trim((string) ($_POST['goal'] ?? '')));

            $errors = $this->validate($user->getUsername(), $user->getEmail(), $user->getStudy(), $user->getSkills(), $user->getGoal());

            // Changement de mot de passe (facultatif) : exige le mot de passe actuel.
            $currentPassword = (string) ($_POST['current_password'] ?? '');
            $newPassword = (string) ($_POST['new_password'] ?? '');
            $newPasswordConfirm = (string) ($_POST['new_password_confirm'] ?? '');
            $changePassword = $currentPassword !== '' || $newPassword !== '' || $newPasswordConfirm !== '';
            if ($changePassword) {
                $errors = array_merge($errors, $this->validateNewPassword($user->getPassword(), $currentPassword, $newPassword, $newPasswordConfirm));
            }

            if ($errors === []) {
                try {
                    $this->updateProfile($userId, $user->getUsername(), $user->getEmail(), $user->getStudy(), $user->getSkills(), $user->getGoal());
                    if ($changePassword) {
                        $this->updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));
                        session_regenerate_id(true); // nouvel identifiant de session après un changement sensible
                    }
                    $_SESSION['username'] = $user->getUsername();
                    flash('success', $changePassword ? 'Votre profil et votre mot de passe ont été mis à jour.' : 'Votre profil a été mis à jour.');
                    redirect_to('profile');
                } catch (PDOException $e) {
                    if ($e->getCode() !== '23000') {
                        throw $e;
                    }
                    $errors[] = 'Cet e-mail ou ce nom d’utilisateur est déjà utilisé par un autre compte.';
                }
            }
        }

        $this->render('front', 'profile', 'Mon profil', 'profile', [
            'user' => $user,
            'errors' => $errors,
            'enrolledCourses' => $this->fetchEnrolledCourses($userId),
        ]);
    }

    /** @return string[] */
    private function validate(string $username, string $email, string $study, string $skills, string $goal): array
    {
        $errors = [];
        if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
            $errors[] = 'Le nom d’utilisateur doit contenir 3 à 50 caractères (lettres, chiffres, _ ou -).';
        }
        if (strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse e-mail invalide.';
        }
        if (mb_strlen($study) > 160) {
            $errors[] = 'La formation actuelle ne doit pas dépasser 160 caractères.';
        }
        if (mb_strlen($skills) > 1000 || mb_strlen($goal) > 1000) {
            $errors[] = 'Les compétences et l’objectif sont limités à 1000 caractères.';
        }

        return $errors;
    }

    /** @return string[] */
    private function validateNewPassword(string $hash, string $current, string $new, string $confirm): array
    {
        $errors = [];
        if (!password_verify($current, $hash)) {
            $errors[] = 'Le mot de passe actuel est incorrect.';
        }
        if (strlen($new) < 8) {
            $errors[] = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
        } elseif (strlen($new) > 72) {
            $errors[] = 'Le nouveau mot de passe ne doit pas dépasser 72 caractères.';
        } elseif (!preg_match('/[A-Za-z]/', $new) || !preg_match('/\d/', $new)) {
            $errors[] = 'Le nouveau mot de passe doit contenir au moins une lettre et un chiffre.';
        }
        if ($new !== $confirm) {
            $errors[] = 'Les deux nouveaux mots de passe ne correspondent pas.';
        }

        return $errors;
    }

    private function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        $stmt->execute(['password' => $hash, 'id' => $id]);
    }

    private function updateProfile(int $id, string $username, string $email, string $study, string $skills, string $goal): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET username = :username, email = :email, study = :study, skills = :skills, goal = :goal WHERE id = :id'
        );
        $stmt->execute([
            'username' => $username, 'email' => $email, 'study' => $study,
            'skills' => $skills, 'goal' => $goal, 'id' => $id,
        ]);
    }
}
