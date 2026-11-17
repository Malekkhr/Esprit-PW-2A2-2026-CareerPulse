<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\User;

final class LoginController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $email = strtolower(trim((string) ($_POST['email'] ?? '')));
            try {
                $user = User::findByEmail($email);
            } catch (\Throwable $e) {
                flash('error', 'Connexion à la base de données impossible.');
                redirect_to('login');
            }

            if ($user === null || !password_verify((string) ($_POST['password'] ?? ''), $user['password_hash'])) {
                flash('error', 'E-mail ou mot de passe incorrect.');
                redirect_to('login');
            }
            if ($user['status'] !== 'active') {
                flash('error', 'Ce compte est suspendu.');
                redirect_to('login');
            }

            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['profile'] = [
                'name' => $user['name'], 'email' => $user['email'], 'study' => $user['study'],
                'skills' => (string) $user['skills'], 'goal' => (string) $user['goal'],
            ];
            flash('success', 'Bienvenue ' . $user['name'] . ' !');
            redirect_to('profile');
        }

        $this->render('front', 'login', 'Connexion', 'login');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
        flash('success', 'Vous êtes déconnecté.');
        redirect_to('home');
    }
}
