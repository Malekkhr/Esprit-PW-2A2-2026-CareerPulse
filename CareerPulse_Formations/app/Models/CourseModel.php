<?php
declare(strict_types=1);

namespace CareerPulse\Models;

/**
 * Une formation (table courses).
 * Modèle (MVC) : uniquement des attributs privés + getters/setters + fromArray().
 * AUCUNE requête SQL et AUCUNE logique métier ici (tout est dans les contrôleurs).
 */
final class CourseModel
{
    private int $id = 0;
    private int $categoryId = 0;
    private string $title = '';
    private string $category = '';
    private string $duration = '';
    private string $level = '';
    private string $image = 'course-1.png';
    private string $skills = '';
    private string $description = '';
    private string $status = 'Publiée';
    private string $createdAt = '';
    private string $enrolledAt = '';

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getSkills(): string
    {
        return $this->skills;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getEnrolledAt(): string
    {
        return $this->enrolledAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setDuration(string $duration): void
    {
        $this->duration = $duration;
    }

    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function setSkills(string $skills): void
    {
        $this->skills = $skills;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setEnrolledAt(string $enrolledAt): void
    {
        $this->enrolledAt = $enrolledAt;
    }

    /** Transforme une ligne SQL en objet. */
    public static function fromArray(array $row): self
    {
        $model = new self();
        $model->setId((int) ($row['id'] ?? 0));
        $model->setCategoryId((int) ($row['category_id'] ?? 0));
        $model->setTitle((string) ($row['title'] ?? ''));
        $model->setCategory((string) ($row['category'] ?? ''));
        $model->setDuration((string) ($row['duration'] ?? ''));
        $model->setLevel((string) ($row['level'] ?? ''));
        $model->setImage((string) ($row['image'] ?? 'course-1.png'));
        $model->setSkills((string) ($row['skills'] ?? ''));
        $model->setDescription((string) ($row['description'] ?? ''));
        $model->setStatus((string) ($row['status'] ?? 'Publiée'));
        $model->setCreatedAt((string) ($row['created_at'] ?? ''));
        $model->setEnrolledAt((string) ($row['enrolled_at'] ?? ''));

        return $model;
    }
}
