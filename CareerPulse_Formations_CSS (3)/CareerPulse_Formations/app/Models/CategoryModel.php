<?php
declare(strict_types=1);

namespace CareerPulse\Models;

/**
 * Une catégorie de formations (table categories).
 * Modèle (MVC) : uniquement des attributs privés + getters/setters + fromArray().
 * AUCUNE requête SQL et AUCUNE logique métier ici (tout est dans les contrôleurs).
 */
final class CategoryModel
{
    private int $id = 0;
    private string $name = '';
    private string $description = '';
    private string $createdAt = '';
    private int $courseCount = 0;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getCourseCount(): int
    {
        return $this->courseCount;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setCourseCount(int $courseCount): void
    {
        $this->courseCount = $courseCount;
    }

    /** Transforme une ligne SQL en objet. */
    public static function fromArray(array $row): self
    {
        $model = new self();
        $model->setId((int) ($row['id'] ?? 0));
        $model->setName((string) ($row['name'] ?? ''));
        $model->setDescription((string) ($row['description'] ?? ''));
        $model->setCreatedAt((string) ($row['created_at'] ?? ''));
        $model->setCourseCount((int) ($row['course_count'] ?? 0));

        return $model;
    }
}
