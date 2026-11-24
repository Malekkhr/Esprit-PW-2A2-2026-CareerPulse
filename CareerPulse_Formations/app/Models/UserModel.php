<?php
declare(strict_types=1);

namespace CareerPulse\Models;

/**
 * Un utilisateur (table users).
 * Modèle (MVC) : uniquement des attributs privés + getters/setters + fromArray().
 * AUCUNE requête SQL et AUCUNE logique métier ici (tout est dans les contrôleurs).
 */
final class UserModel
{
    private int $id = 0;
    private string $username = '';
    private string $email = '';
    private string $password = '';
    private string $role = 'user';
    private string $study = '';
    private string $skills = '';
    private string $goal = '';
    private string $status = 'active';
    private string $createdAt = '';

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getStudy(): string
    {
        return $this->study;
    }

    public function getSkills(): string
    {
        return $this->skills;
    }

    public function getGoal(): string
    {
        return $this->goal;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setStudy(string $study): void
    {
        $this->study = $study;
    }

    public function setSkills(string $skills): void
    {
        $this->skills = $skills;
    }

    public function setGoal(string $goal): void
    {
        $this->goal = $goal;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /** Transforme une ligne SQL en objet. */
    public static function fromArray(array $row): self
    {
        $model = new self();
        $model->setId((int) ($row['id'] ?? 0));
        $model->setUsername((string) ($row['username'] ?? ''));
        $model->setEmail((string) ($row['email'] ?? ''));
        $model->setPassword((string) ($row['password'] ?? ''));
        $model->setRole((string) ($row['role'] ?? 'user'));
        $model->setStudy((string) ($row['study'] ?? ''));
        $model->setSkills((string) ($row['skills'] ?? ''));
        $model->setGoal((string) ($row['goal'] ?? ''));
        $model->setStatus((string) ($row['status'] ?? 'active'));
        $model->setCreatedAt((string) ($row['created_at'] ?? ''));

        return $model;
    }
}
