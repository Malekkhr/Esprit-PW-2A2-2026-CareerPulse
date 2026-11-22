<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Models\UserModel;

/** Backoffice : liste des comptes + suspension / réactivation. */
final class UserController extends AdminController
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $targetId = (int) ($_POST['user_id'] ?? 0);

            if ($targetId === (int) $_SESSION['user_id']) {
                flash('error', 'Vous ne pouvez pas suspendre votre propre compte.');
            } elseif ($targetId > 0) {
                $stmt = $this->db->prepare("UPDATE users SET status = IF(status = 'active', 'suspended', 'active') WHERE id = :id");
                $stmt->execute(['id' => $targetId]);
                flash('success', 'Le statut du compte a été modifié.');
            }
            redirect_to('admin/users');
        }

        $rows = $this->db->query('SELECT * FROM users ORDER BY id ASC')->fetchAll();

        $this->render('admin', 'users', 'Utilisateurs', 'admin/users', [
            'users' => array_map(static fn (array $row): UserModel => UserModel::fromArray($row), $rows),
        ]);
    }
}
