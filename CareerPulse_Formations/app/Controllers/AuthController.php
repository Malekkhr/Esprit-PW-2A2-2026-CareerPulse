<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Database;
use CareerPulse\Models\UserModel;
use PDOException;

/**
 * AuthController : TOUTE la logique d'authentification (workshop Sessions & Sécurité).
 *  - inscription (validation serveur, hachage password_hash, rôle forcé à « user »)
 *  - connexion / déconnexion (password_verify, session_regenerate_id)
 *  - contrôle d'accès : isAuthenticated(), requireAuth(), hasRole(), requireRole()
 *  - protection CSRF des formulaires d'authentification
 *  - espace utilisateur (frontoffice) : dashboard
 */
final class AuthController extends DatabaseController
{
    use UserQueries;
    use CatalogQueries;

    /** Protection anti force brute : 5 échecs => connexion bloquée 5 minutes. */
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 300;
    /** Empreinte bcrypt factice : permet de faire un password_verify() même si l'e-mail n'existe pas (temps de réponse constant). */
    private const DUMMY_HASH = '$2y$10$8Q8RbTz.9OGHlaXJ6FM2KeZSaV4cBIMsGk6HCPcM1ywOaHdpY4aeq';

    // ======================================================================
    // ÉTAT DE LA SESSION + CONTRÔLE DES RÔLES (méthodes statiques)
    // ======================================================================
    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function hasRole(string $role): bool
    {
        return self::isAuthenticated() && ($_SESSION['role'] ?? null) === $role;
    }

    /**
     * Redirige vers la connexion (et arrête le script) si personne n'est connecté.
     * Le compte est AUSSI revérifié en base à chaque appel : un compte supprimé ou suspendu
     * perd son accès immédiatement, y compris dans le frontoffice (inscription aux formations, profil...).
     */
    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            flash('error', 'Connectez-vous pour accéder à cette page.');
            redirect_to('login');
        }

        $stmt = Database::getConnexion()->prepare('SELECT role, status FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => (int) $_SESSION['user_id']]);
        $row = $stmt->fetch();

        if (!$row || $row['status'] !== 'active') {
            self::destroySession();
            session_start();
            flash('error', 'Votre compte n’est plus disponible. Reconnectez-vous.');
            redirect_to('login');
        }

        $_SESSION['role'] = (string) $row['role']; // resynchronise la session avec la base
    }

    /**
     * Bloque l'accès (403) si l'utilisateur n'a pas le rôle exigé.
     * Le rôle est relu en base À CHAQUE appel (via requireAuth) et pas seulement à la connexion :
     * un admin rétrogradé ou un compte suspendu perd son accès immédiatement.
     */
    public static function requireRole(string $role): void
    {
        self::requireAuth();

        if ($_SESSION['role'] !== $role) {
            (new ErrorController())->forbidden();
            exit;
        }
    }

    /** Page d'atterrissage selon le rôle : admin => backoffice, user => frontoffice. */
    public static function landingAction(): string
    {
        return ($_SESSION['role'] ?? '') === 'admin' ? 'admin' : 'dashboard';
    }

    // ======================================================================
    // CSRF
    // ======================================================================
    public function getCsrfToken(): string
    {
        return csrf_token();
    }

    // ======================================================================
    // INSCRIPTION
    // ======================================================================
    /** Affiche le formulaire (GET) ou traite l'inscription (POST). */
    public function registerPage(): void
    {
        if (self::isAuthenticated()) {
            redirect_to(self::landingAction());
        }

        $errors = [];
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->register($_POST);
            $old = [
                'username' => trim((string) ($_POST['username'] ?? '')),
                'email' => trim((string) ($_POST['email'] ?? '')),
                'study' => trim((string) ($_POST['study'] ?? '')),
            ];
            if ($errors === []) {
                flash('success', 'Compte créé avec succès, vous pouvez maintenant vous connecter.');
                redirect_to('login');
            }
        }

        $this->render('front', 'register', 'Inscription', 'register', ['errors' => $errors, 'old' => $old]);
    }

    /** Valide, hache et enregistre. Retourne la liste des erreurs (vide = succès). */
    public function register(array $post): array
    {
        $errors = [];

        if (!verify_csrf((string) ($post['csrf_token'] ?? ''))) {
            return ['Jeton de sécurité invalide. Veuillez réessayer.'];
        }

        $username = trim((string) ($post['username'] ?? ''));
        $email = strtolower(trim((string) ($post['email'] ?? '')));
        $study = trim((string) ($post['study'] ?? ''));
        $password = (string) ($post['password'] ?? '');
        $passwordConfirm = (string) ($post['password_confirm'] ?? '');

        if ($username === '' || $email === '' || $password === '') {
            $errors[] = 'Tous les champs obligatoires doivent être remplis.';
        }
        if ($username !== '' && !preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
            $errors[] = 'Le nom d’utilisateur doit contenir 3 à 50 caractères (lettres, chiffres, _ ou -).';
        }
        if ($email !== '' && (strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            $errors[] = 'Adresse e-mail invalide.';
        }
        if (mb_strlen($study) > 160) {
            $errors[] = 'La formation actuelle ne doit pas dépasser 160 caractères.';
        }
        if ($password !== '') {
            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            } elseif (strlen($password) > 72) {
                $errors[] = 'Le mot de passe ne doit pas dépasser 72 caractères.';
            } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins une lettre et un chiffre.';
            }
        }
        if ($password !== $passwordConfirm) {
            $errors[] = 'Les deux mots de passe ne correspondent pas.';
        }
        if ($errors !== []) {
            return $errors;
        }

        if ($this->existsByEmailOrUsername($email, $username)) {
            return ['Cet e-mail ou ce nom d’utilisateur est déjà utilisé.'];
        }

        // Hachage sécurisé (bcrypt) : le mot de passe n'est JAMAIS stocké en clair.
        $user = new UserModel();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setStudy($study);
        $user->setRole('user'); // rôle forcé : aucune auto-promotion en admin possible

        try {
            $this->insertUser($user);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // contrainte UNIQUE (course de concurrence)
                return ['Cet e-mail ou ce nom d’utilisateur est déjà utilisé.'];
            }
            throw $e;
        }

        return [];
    }

    private function existsByEmailOrUsername(string $email, string $username): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
        $stmt->execute(['email' => $email, 'username' => $username]);

        return (bool) $stmt->fetch();
    }

    private function insertUser(UserModel $user): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role, study) VALUES (:username, :email, :password, :role, :study)'
        );
        $stmt->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'study' => $user->getStudy(),
        ]);
    }

    // ======================================================================
    // CONNEXION / DÉCONNEXION
    // ======================================================================
    /** Affiche le formulaire (GET) ou vérifie les identifiants (POST). */
    public function loginPage(): void
    {
        if (self::isAuthenticated()) {
            redirect_to(self::landingAction());
        }

        $errors = [];
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->login($_POST);
            $old = ['email' => trim((string) ($_POST['email'] ?? ''))];
            if ($errors === []) {
                redirect_to(self::landingAction());
            }
        }

        $this->render('front', 'login', 'Connexion', 'login', ['errors' => $errors, 'old' => $old]);
    }

    /** Vérifie les identifiants et ouvre la session. Retourne les erreurs (vide = succès). */
    public function login(array $post): array
    {
        if (!verify_csrf((string) ($post['csrf_token'] ?? ''))) {
            return ['Jeton de sécurité invalide. Veuillez réessayer.'];
        }

        // Anti force brute : trop d'échecs récents => on refuse même un bon mot de passe pendant quelques minutes.
        $lockedUntil = (int) ($_SESSION['login_locked_until'] ?? 0);
        if ($lockedUntil > time()) {
            $minutes = (int) ceil(($lockedUntil - time()) / 60);
            return ['Trop de tentatives échouées. Réessayez dans ' . $minutes . ' minute(s).'];
        }

        $email = strtolower(trim((string) ($post['email'] ?? '')));
        $password = (string) ($post['password'] ?? '');

        if ($email === '' || $password === '') {
            return ['Veuillez saisir votre e-mail et votre mot de passe.'];
        }
        if (strlen($email) > 150 || strlen($password) > 72) {
            $this->registerFailedLogin();
            return ['E-mail ou mot de passe incorrect.'];
        }

        $user = $this->findUserByEmail($email);

        // Message volontairement générique : on ne révèle jamais si l'e-mail existe.
        // password_verify() est exécuté dans tous les cas pour que le temps de réponse ne trahisse pas l'existence du compte.
        $hash = $user !== null ? $user->getPassword() : self::DUMMY_HASH;
        $passwordOk = password_verify($password, $hash);
        if ($user === null || !$passwordOk) {
            $this->registerFailedLogin();
            return ['E-mail ou mot de passe incorrect.'];
        }
        if ($user->getStatus() !== 'active') {
            return ['Ce compte est suspendu. Contactez un administrateur.'];
        }

        // Nouvel identifiant de session : protège contre la fixation de session.
        session_regenerate_id(true);
        unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['role'] = $user->getRole(); // recopié depuis la base, jamais choisi par le visiteur
        $_SESSION['last_activity'] = time();

        return [];
    }

    /** Compte un échec de connexion ; au-delà du seuil, bloque les tentatives pendant LOCKOUT_SECONDS. */
    private function registerFailedLogin(): void
    {
        $attempts = (int) ($_SESSION['login_attempts'] ?? 0) + 1;
        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $_SESSION['login_locked_until'] = time() + self::LOCKOUT_SECONDS;
            $attempts = 0;
        }
        $_SESSION['login_attempts'] = $attempts;
    }

    /**
     * Route « logout » : accepte uniquement un POST avec jeton CSRF valide
     * (un simple lien GET ne peut donc plus déconnecter un utilisateur à son insu).
     */
    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            redirect_to(self::isAuthenticated() ? self::landingAction() : 'login');
        }
        $this->forceLogout();
    }

    /** Détruit la session (données serveur + cookie) puis renvoie vers la connexion. */
    public function forceLogout(): void
    {
        self::destroySession();
        session_start(); // nouvelle session vide, uniquement pour afficher le message
        flash('success', 'Vous êtes déconnecté.');
        redirect_to('login');
    }

    private static function destroySession(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    // ======================================================================
    // ESPACE UTILISATEUR (frontoffice)
    // ======================================================================
    public function dashboard(): void
    {
        self::requireAuth();

        $user = $this->findUserById((int) $_SESSION['user_id']);
        if ($user === null || $user->getStatus() !== 'active') { // compte supprimé/suspendu alors que la session est active
            $this->forceLogout();
        }

        $this->render('front', 'dashboard', 'Mon espace', 'dashboard', [
            'user' => $user,
            'enrolledCourses' => $this->fetchEnrolledCourses($user->getId()),
        ]);
    }
}
