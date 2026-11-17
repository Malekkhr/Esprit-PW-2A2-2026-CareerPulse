<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\Controller;
use CareerPulse\Models\User;

final class UserController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            User::toggleStatus((int) ($_POST['user_id'] ?? 0));
            flash('success', 'Le statut du compte a été modifié.');
            redirect_to('admin/users');
        }

        try {
            $users = array_map(static fn (array $u): array => [
                'id' => $u['id'], 'name' => $u['name'], 'email' => $u['email'],
                'program' => $u['study'], 'status' => $u['status'] === 'active' ? 'Actif' : 'Suspendu',
            ], User::all());
        } catch (\Throwable $e) {
            flash('error', 'Base de données indisponible.');
            $users = [];
        }

        $this->render('admin', 'users', 'Utilisateurs', 'admin/users', ['users' => $users]);
    }
}
