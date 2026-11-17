<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\User;

final class RegisterController extends Controller
{
    public function index(): void
    {
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $old = [
                'name' => trim((string) ($_POST['name'] ?? '')),
                'email' => strtolower(trim((string) ($_POST['email'] ?? ''))),
                'study' => trim((string) ($_POST['study'] ?? '')),
            ];
            $password = (string) ($_POST['password'] ?? '');

            if ($old['name'] === '' || filter_var($old['email'], FILTER_VALIDATE_EMAIL) === false) {
                flash('error', 'Ajoutez un nom et une adresse e-mail valide.');
            } elseif (strlen($password) < 8) {
                flash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            } elseif ($password !== (string) ($_POST['password2'] ?? '')) {
                flash('error', 'Les deux mots de passe ne correspondent pas.');
            } else {
                try {
                    $id = User::create($old['name'], $old['email'], $password, $old['study']);
                } catch (\Throwable $e) {
                    flash('error', 'Connexion à la base de données impossible. Vérifiez MySQL et config/database.php.');
                    $id = false;
                }
                if ($id === null) {
                    flash('error', 'Cette adresse e-mail est déjà inscrite.');
                } elseif ($id !== false) {
                    flash('success', 'Inscription réussie ! Vous pouvez vous connecter.');
                    redirect_to('login');
                }
            }
        }

        $this->render('front', 'register', 'Inscription', 'register', ['old' => $old]);
    }
}
