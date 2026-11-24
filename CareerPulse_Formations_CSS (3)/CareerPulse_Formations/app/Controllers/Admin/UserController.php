<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Models\UserModel;
use PDOException;

/** Back-office CRUD des comptes : création, consultation, modification et suppression. */
final class UserController extends AdminController
{
    private const ROLES = ['user', 'admin'];
    private const STATUSES = ['active', 'suspended'];

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->postIsValid()) {
                $this->handlePost();
            }
            redirect_to('admin/users');
        }

        $rows = $this->db->query('SELECT * FROM users ORDER BY id ASC')->fetchAll();
        $editId = (int) ($_GET['edit'] ?? 0);
        $editingUser = $editId > 0 ? $this->findUser($editId) : null;

        $this->render('admin', 'users', 'Gestion des utilisateurs', 'admin/users', [
            'users' => array_map(static fn (array $row): UserModel => UserModel::fromArray($row), $rows),
            'editingUser' => $editingUser,
        ]);
    }

    private function handlePost(): void
    {
        $action = (string) ($_POST['action'] ?? '');
        $id = (int) ($_POST['user_id'] ?? 0);

        if ($action === 'delete') {
            $this->deleteUser($id);
            return;
        }
        if ($action === 'status') {
            $this->changeStatus($id, (string) ($_POST['status'] ?? ''));
            return;
        }
        if (!in_array($action, ['create', 'update'], true)) {
            flash('error', 'Action utilisateur inconnue.');
            return;
        }

        $errors = $this->validateInput($_POST, $action, $id);
        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            return;
        }

        $username = trim((string) $_POST['username']);
        $email = strtolower(trim((string) $_POST['email']));
        $study = trim((string) ($_POST['study'] ?? ''));
        $skills = trim((string) ($_POST['skills'] ?? ''));
        $goal = trim((string) ($_POST['goal'] ?? ''));
        $role = (string) $_POST['role'];
        $status = (string) $_POST['status'];
        $password = (string) ($_POST['password'] ?? '');

        if ($this->identityExists($username, $email, $action === 'update' ? $id : 0)) {
            flash('error', 'Cet e-mail ou ce nom d’utilisateur est déjà utilisé.');
            return;
        }

        if ($action === 'update' && $id === (int) $_SESSION['user_id']) {
            $current = $this->findUser($id);
            if ($current === null || $role !== $current->getRole() || $status !== $current->getStatus()) {
                flash('error', 'Vous ne pouvez pas modifier votre propre rôle ou statut ici.');
                return;
            }
        }

        if ($action === 'update' && !$this->roleChangeKeepsAnActiveAdmin($id, $role, $status)) {
            flash('error', 'Il faut conserver au moins un administrateur actif.');
            return;
        }

        try {
            if ($action === 'create') {
                $stmt = $this->db->prepare(
                    'INSERT INTO users (username, email, password, role, study, skills, goal, status)
                     VALUES (:username, :email, :password, :role, :study, :skills, :goal, :status)'
                );
                $stmt->execute([
                    'username' => $username, 'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $role, 'study' => $study, 'skills' => $skills,
                    'goal' => $goal, 'status' => $status,
                ]);
                flash('success', 'Le compte utilisateur a été créé.');
            } else {
                $sql = 'UPDATE users SET username = :username, email = :email, role = :role,
                        status = :status, study = :study, skills = :skills, goal = :goal';
                $params = [
                    'username' => $username, 'email' => $email, 'role' => $role,
                    'status' => $status, 'study' => $study, 'skills' => $skills,
                    'goal' => $goal, 'id' => $id,
                ];
                if ($password !== '') {
                    $sql .= ', password = :password';
                    $params['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
                $sql .= ' WHERE id = :id';
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                flash('success', 'Le compte utilisateur a été mis à jour.');
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                flash('error', 'Cet e-mail ou ce nom d’utilisateur est déjà utilisé.');
                return;
            }
            throw $e;
        }
    }

    private function validateInput(array $data, string $action, int $id): array
    {
        $errors = [];
        $username = trim((string) ($data['username'] ?? ''));
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $study = trim((string) ($data['study'] ?? ''));
        $skills = trim((string) ($data['skills'] ?? ''));
        $goal = trim((string) ($data['goal'] ?? ''));
        $role = (string) ($data['role'] ?? '');
        $status = (string) ($data['status'] ?? '');
        $password = (string) ($data['password'] ?? '');
        $confirmation = (string) ($data['password_confirm'] ?? '');

        if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
            $errors[] = 'Le nom d’utilisateur doit contenir 3 à 50 caractères (lettres, chiffres, _ ou -).';
        }
        if (strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse e-mail invalide.';
        }
        if (mb_strlen($study) > 160 || mb_strlen($skills) > 1000 || mb_strlen($goal) > 1000) {
            $errors[] = 'La formation est limitée à 160 caractères; compétences et objectif à 1000 caractères.';
        }
        if (!in_array($role, self::ROLES, true) || !in_array($status, self::STATUSES, true)) {
            $errors[] = 'Le rôle ou le statut sélectionné est invalide.';
        }
        if ($action === 'create' || $password !== '' || $confirmation !== '') {
            if (strlen($password) < 8 || strlen($password) > 72
                || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
                $errors[] = 'Le mot de passe doit contenir 8 à 72 caractères, au moins une lettre et un chiffre.';
            }
            if ($password !== $confirmation) {
                $errors[] = 'Les deux mots de passe ne correspondent pas.';
            }
        }
        if ($action === 'update' && ($id <= 0 || $this->findUser($id) === null)) {
            $errors[] = 'Le compte à modifier est introuvable.';
        }

        return $errors;
    }

    private function identityExists(string $username, string $email, int $exceptId): bool
    {
        $sql = 'SELECT id FROM users WHERE (username = :username OR email = :email)';
        $params = ['username' => $username, 'email' => $email];
        if ($exceptId > 0) {
            $sql .= ' AND id <> :except_id';
            $params['except_id'] = $exceptId;
        }
        $sql .= ' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }

    private function changeStatus(int $id, string $status): void
    {
        $user = $this->findUser($id);
        if ($user === null || !in_array($status, self::STATUSES, true)) {
            flash('error', 'Utilisateur ou statut invalide.');
            return;
        }
        if ($id === (int) $_SESSION['user_id']) {
            flash('error', 'Vous ne pouvez pas suspendre votre propre compte.');
            return;
        }
        if (!$this->roleChangeKeepsAnActiveAdmin($id, $user->getRole(), $status)) {
            flash('error', 'Il faut conserver au moins un administrateur actif.');
            return;
        }
        $stmt = $this->db->prepare('UPDATE users SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
        flash('success', 'Le statut du compte a été modifié.');
    }

    private function deleteUser(int $id): void
    {
        $user = $this->findUser($id);
        if ($user === null) {
            flash('error', 'Utilisateur introuvable.');
            return;
        }
        if ($id === (int) $_SESSION['user_id']) {
            flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }
        if (!$this->roleChangeKeepsAnActiveAdmin($id, 'user', 'suspended')) {
            flash('error', 'Impossible de supprimer le dernier administrateur actif.');
            return;
        }
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        flash('success', 'Le compte utilisateur a été supprimé.');
    }

    private function roleChangeKeepsAnActiveAdmin(int $id, string $newRole, string $newStatus): bool
    {
        $current = $this->findUser($id);
        if ($current === null || ($current->getRole() !== 'admin' || $current->getStatus() !== 'active')) {
            return true;
        }
        if ($newRole === 'admin' && $newStatus === 'active') {
            return true;
        }
        $count = (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'admin' AND status = 'active'")->fetchColumn();
        return $count > 1;
    }

    private function findUser(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }
}
