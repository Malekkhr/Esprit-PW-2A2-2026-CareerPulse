<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\CatalogQueries;
use CareerPulse\Controllers\UserQueries;
use CareerPulse\Models\UserModel;
use PDOException;

/**
 * Backoffice : CRUD complet des utilisateurs + gestion des rôles et des statuts.
 *
 *  index   : liste (+ recherche et filtre par rôle)      GET  admin/users
 *  show    : fiche détaillée d'un utilisateur            GET  admin/users/show?id=
 *  create  : ajout d'un utilisateur                      GET/POST admin/users/create
 *  edit    : modification d'un utilisateur               GET/POST admin/users/edit?id=
 *  delete  : suppression                                 POST admin/users/delete
 *  role    : changement de rôle (user <-> admin)         POST admin/users/role
 *  status  : suspension / réactivation                   POST admin/users/status
 *
 * L'accès est réservé au rôle « admin » (contrôle dans AdminController::__construct).
 * Toutes les écritures sont des POST protégés par jeton CSRF + requêtes préparées.
 */
final class UserController extends AdminController
{
    use UserQueries;
    use CatalogQueries;

    private const ROLES = ['user', 'admin'];
    private const STATUSES = ['active', 'suspended'];

    // ======================================================================
    // READ : liste + fiche
    // ======================================================================
    public function index(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        $roleFilter = (string) ($_GET['role'] ?? '');
        if (!in_array($roleFilter, self::ROLES, true)) {
            $roleFilter = '';
        }

        $this->render('admin', 'users', 'Utilisateurs', 'admin/users', [
            'users' => $this->fetchUsers($search, $roleFilter),
            'totalUsers' => $this->countUsers(),
            'search' => $search,
            'roleFilter' => $roleFilter,
            'roles' => self::ROLES,
        ]);
    }

    public function show(): void
    {
        $user = $this->findUserOrRedirect((int) ($_GET['id'] ?? 0));

        $this->render('admin', 'user-show', 'Fiche utilisateur', 'admin/users', [
            'account' => $user,
            'enrolledCourses' => $this->fetchEnrolledCourses($user->getId()),
        ]);
    }

    // ======================================================================
    // CREATE
    // ======================================================================
    public function create(): void
    {
        $user = new UserModel();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $user->setUsername(trim((string) ($_POST['username'] ?? '')));
            $user->setEmail(strtolower(trim((string) ($_POST['email'] ?? ''))));
            $user->setStudy(trim((string) ($_POST['study'] ?? '')));
            $user->setRole($this->sanitizeRole((string) ($_POST['role'] ?? 'user')));
            $user->setStatus($this->sanitizeStatus((string) ($_POST['status'] ?? 'active')));
            $password = (string) ($_POST['password'] ?? '');
            $confirm = (string) ($_POST['password_confirm'] ?? '');

            $errors = $this->validateIdentity($user->getUsername(), $user->getEmail(), $user->getStudy());
            if ($password === '') {
                $errors[] = 'Le mot de passe est obligatoire.';
            } else {
                $errors = array_merge($errors, $this->validatePassword($password, $confirm));
            }

            if ($errors === []) {
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
                try {
                    $this->insertUser($user);
                    flash('success', 'L’utilisateur « ' . $user->getUsername() . ' » a été créé.');
                    redirect_to('admin/users');
                } catch (PDOException $e) {
                    if ($e->getCode() !== '23000') {
                        throw $e;
                    }
                    $errors[] = 'Cet e-mail ou ce nom d’utilisateur est déjà utilisé.';
                }
            }
        }

        $this->renderForm($user, $errors, false);
    }

    // ======================================================================
    // UPDATE
    // ======================================================================
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? $_POST['user_id'] ?? 0);
        $user = $this->findUserOrRedirect($id);
        $isSelf = $id === (int) $_SESSION['user_id'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $user->setUsername(trim((string) ($_POST['username'] ?? '')));
            $user->setEmail(strtolower(trim((string) ($_POST['email'] ?? ''))));
            $user->setStudy(trim((string) ($_POST['study'] ?? '')));
            $user->setSkills(trim((string) ($_POST['skills'] ?? '')));
            $user->setGoal(trim((string) ($_POST['goal'] ?? '')));

            // Un admin ne peut pas modifier son propre rôle / statut (anti auto-verrouillage).
            $newRole = $isSelf ? $user->getRole() : $this->sanitizeRole((string) ($_POST['role'] ?? $user->getRole()));
            $newStatus = $isSelf ? $user->getStatus() : $this->sanitizeStatus((string) ($_POST['status'] ?? $user->getStatus()));

            $errors = $this->validateIdentity($user->getUsername(), $user->getEmail(), $user->getStudy());
            if (mb_strlen($user->getSkills()) > 1000 || mb_strlen($user->getGoal()) > 1000) {
                $errors[] = 'Les compétences et l’objectif sont limités à 1000 caractères.';
            }

            // Mot de passe : facultatif en modification (vide = inchangé).
            $password = (string) ($_POST['password'] ?? '');
            $confirm = (string) ($_POST['password_confirm'] ?? '');
            if ($password !== '' || $confirm !== '') {
                $errors = array_merge($errors, $this->validatePassword($password, $confirm));
            }

            if ($this->wouldRemoveLastAdmin($user, $newRole, $newStatus)) {
                $errors[] = 'Opération refusée : il doit rester au moins un administrateur actif.';
            }

            if ($errors === []) {
                $user->setRole($newRole);
                $user->setStatus($newStatus);
                if ($password !== '') {
                    $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
                }
                try {
                    $this->updateUser($user, $password !== '');
                    if ($isSelf) {
                        $_SESSION['username'] = $user->getUsername();
                    }
                    flash('success', 'L’utilisateur « ' . $user->getUsername() . ' » a été mis à jour.');
                    redirect_to('admin/users');
                } catch (PDOException $e) {
                    if ($e->getCode() !== '23000') {
                        throw $e;
                    }
                    $errors[] = 'Cet e-mail ou ce nom d’utilisateur est déjà utilisé par un autre compte.';
                }
            }
        }

        $this->renderForm($user, $errors, true, $isSelf);
    }

    // ======================================================================
    // DELETE
    // ======================================================================
    public function delete(): void
    {
        $this->requirePost();

        $target = $this->findUserOrRedirect((int) ($_POST['user_id'] ?? 0));

        if ($target->getId() === (int) $_SESSION['user_id']) {
            flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        } elseif ($this->wouldRemoveLastAdmin($target, null, null)) {
            flash('error', 'Opération refusée : il doit rester au moins un administrateur actif.');
        } else {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $target->getId()]); // les inscriptions sont supprimées en cascade (FK)
            flash('success', 'L’utilisateur « ' . $target->getUsername() . ' » a été supprimé.');
        }
        redirect_to('admin/users');
    }

    // ======================================================================
    // ROLES + STATUT
    // ======================================================================
    public function role(): void
    {
        $this->requirePost();

        $target = $this->findUserOrRedirect((int) ($_POST['user_id'] ?? 0));
        $newRole = (string) ($_POST['role'] ?? '');

        if (!in_array($newRole, self::ROLES, true)) {
            flash('error', 'Rôle invalide.');
        } elseif ($target->getId() === (int) $_SESSION['user_id']) {
            flash('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        } elseif ($this->wouldRemoveLastAdmin($target, $newRole, null)) {
            flash('error', 'Opération refusée : il doit rester au moins un administrateur actif.');
        } else {
            $stmt = $this->db->prepare('UPDATE users SET role = :role WHERE id = :id');
            $stmt->execute(['role' => $newRole, 'id' => $target->getId()]);
            flash('success', 'Le rôle de « ' . $target->getUsername() . ' » est maintenant « ' . $newRole . ' ».');
        }
        redirect_to('admin/users');
    }

    public function status(): void
    {
        $this->requirePost();

        $target = $this->findUserOrRedirect((int) ($_POST['user_id'] ?? 0));
        $newStatus = $target->getStatus() === 'active' ? 'suspended' : 'active';

        if ($target->getId() === (int) $_SESSION['user_id']) {
            flash('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        } elseif ($this->wouldRemoveLastAdmin($target, null, $newStatus)) {
            flash('error', 'Opération refusée : il doit rester au moins un administrateur actif.');
        } else {
            $stmt = $this->db->prepare('UPDATE users SET status = :status WHERE id = :id');
            $stmt->execute(['status' => $newStatus, 'id' => $target->getId()]);
            flash('success', 'Le statut du compte a été modifié.');
        }
        redirect_to('admin/users');
    }

    // ======================================================================
    // Outils internes
    // ======================================================================
    /** Les actions d'écriture n'acceptent que des POST avec jeton CSRF valide. */
    private function requirePost(): void
    {
        if (!$this->postIsValid()) {
            redirect_to('admin/users');
        }
    }

    private function findUserOrRedirect(int $id): UserModel
    {
        $user = $id > 0 ? $this->findUserById($id) : null;
        if ($user === null) {
            flash('error', 'Utilisateur introuvable.');
            redirect_to('admin/users');
        }

        return $user;
    }

    private function renderForm(UserModel $user, array $errors, bool $isEdit, bool $isSelf = false): void
    {
        $this->render('admin', 'user-form', $isEdit ? 'Modifier un utilisateur' : 'Ajouter un utilisateur', 'admin/users', [
            'account' => $user,
            'errors' => $errors,
            'isEdit' => $isEdit,
            'isSelf' => $isSelf,
            'roles' => self::ROLES,
            'statuses' => self::STATUSES,
        ]);
    }

    private function sanitizeRole(string $role): string
    {
        return in_array($role, self::ROLES, true) ? $role : 'user';
    }

    private function sanitizeStatus(string $status): string
    {
        return in_array($status, self::STATUSES, true) ? $status : 'active';
    }

    /** @return string[] */
    private function validateIdentity(string $username, string $email, string $study): array
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

        return $errors;
    }

    /** @return string[] */
    private function validatePassword(string $password, string $confirm): array
    {
        $errors = [];
        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        } elseif (strlen($password) > 72) {
            $errors[] = 'Le mot de passe ne doit pas dépasser 72 caractères.';
        } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre et un chiffre.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Les deux mots de passe ne correspondent pas.';
        }

        return $errors;
    }

    /**
     * Vrai si l'opération ferait disparaître le dernier administrateur actif.
     * $newRole / $newStatus = null : la valeur actuelle est conservée ; pour une
     * suppression, passer null, null (le compte disparaît).
     */
    private function wouldRemoveLastAdmin(UserModel $target, ?string $newRole, ?string $newStatus): bool
    {
        if ($target->getRole() !== 'admin' || $target->getStatus() !== 'active') {
            return false; // ce compte ne compte pas parmi les admins actifs
        }

        $staysAdminActive = ($newRole ?? 'admin') === 'admin' && ($newStatus ?? 'active') === 'active';
        $isDeletion = $newRole === null && $newStatus === null;
        if ($staysAdminActive && !$isDeletion) {
            return false;
        }

        return $this->countActiveAdmins() <= 1;
    }

    // ---------------------------- Requêtes SQL ----------------------------
    /** @return UserModel[] */
    private function fetchUsers(string $search, string $role): array
    {
        $sql = 'SELECT * FROM users WHERE 1 = 1';
        $params = [];
        if ($search !== '') {
            $sql .= ' AND (username LIKE :q OR email LIKE :q2 OR study LIKE :q3)';
            $like = '%' . addcslashes($search, '%_\\') . '%';
            $params['q'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
        }
        if ($role !== '') {
            $sql .= ' AND role = :role';
            $params['role'] = $role;
        }
        $sql .= ' ORDER BY id ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return array_map(static fn (array $row): UserModel => UserModel::fromArray($row), $stmt->fetchAll());
    }

    private function countUsers(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    private function countActiveAdmins(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'admin' AND status = 'active'")->fetchColumn();
    }

    private function insertUser(UserModel $user): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role, study, status)
             VALUES (:username, :email, :password, :role, :study, :status)'
        );
        $stmt->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'study' => $user->getStudy(),
            'status' => $user->getStatus(),
        ]);
    }

    private function updateUser(UserModel $user, bool $withPassword): void
    {
        $sql = 'UPDATE users SET username = :username, email = :email, study = :study, skills = :skills,
                goal = :goal, role = :role, status = :status';
        $params = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'study' => $user->getStudy(),
            'skills' => $user->getSkills(),
            'goal' => $user->getGoal(),
            'role' => $user->getRole(),
            'status' => $user->getStatus(),
            'id' => $user->getId(),
        ];
        if ($withPassword) {
            $sql .= ', password = :password';
            $params['password'] = $user->getPassword();
        }
        $sql .= ' WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }
}
