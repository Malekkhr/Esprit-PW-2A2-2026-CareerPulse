<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\UserModel;

/**
 * Requêtes SQL préparées sur la table users, partagées entre contrôleurs.
 * (Le SQL vit dans la couche Contrôleur, jamais dans les Modèles ni les Vues.)
 * Nécessite la propriété $this->db (PDO).
 */
trait UserQueries
{
    private function findUserById(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? UserModel::fromArray($row) : null;
    }

    private function findUserByEmail(string $email): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ? UserModel::fromArray($row) : null;
    }
}
