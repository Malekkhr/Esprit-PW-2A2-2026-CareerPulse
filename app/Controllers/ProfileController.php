<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\User;

final class ProfileController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $profile = [
                'name' => trim((string) ($_POST['name'] ?? '')),
                'email' => trim((string) ($_POST['email'] ?? '')),
                'study' => trim((string) ($_POST['study'] ?? '')),
                'skills' => trim((string) ($_POST['skills'] ?? '')),
                'goal' => trim((string) ($_POST['goal'] ?? '')),
            ];

            if ($profile['name'] === '' || filter_var($profile['email'], FILTER_VALIDATE_EMAIL) === false) {
                flash('error', 'Ajoutez un nom et une adresse e-mail valide.');
            } else {
                $_SESSION['profile'] = $profile;
                if (!empty($_SESSION['user_id'])) {
                    try {
                        User::updateProfile((int) $_SESSION['user_id'], $profile);
                    } catch (\Throwable $e) {
                        flash('error', 'Email déjà utilisé par un autre compte.');
                        redirect_to('profile');
                    }
                }
                flash('success', 'Votre profil de démonstration a été mis à jour.');
            }
            redirect_to('profile');
        }

        $this->render('front', 'profile', 'Mon profil', 'profile', ['profile' => User::sessionProfile()]);
    }
}
