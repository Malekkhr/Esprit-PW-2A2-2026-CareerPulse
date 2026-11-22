<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\CatalogQueries;
use PDOException;

final class CategoryController extends AdminController
{
    use CatalogQueries;

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $action = (string) ($_POST['action'] ?? 'create');

            if ($action === 'delete') {
                $id = (int) ($_POST['category_id'] ?? 0);
                if ($id > 0) {
                    $this->deleteCategory($id);
                    flash('success', 'La catégorie a été supprimée.');
                }
                redirect_to('admin/categories');
            }

            $name = trim((string) ($_POST['name'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));

            if ($name === '' || mb_strlen($name) > 100 || mb_strlen($description) > 255) {
                flash('error', 'Le nom (100 caractères max.) est obligatoire ; la description est limitée à 255 caractères.');
                redirect_to('admin/categories');
            }

            if ($action === 'update') {
                $id = (int) ($_POST['category_id'] ?? 0);
                if ($id > 0) {
                    $ok = $this->updateCategory($id, $name, $description);
                    flash($ok ? 'success' : 'error', $ok ? 'Catégorie mise à jour.' : 'Une catégorie porte déjà ce nom.');
                }
            } else {
                $created = $this->createCategory($name, $description);
                flash($created ? 'success' : 'error', $created ? 'Catégorie créée avec succès.' : 'Cette catégorie existe déjà.');
            }
            redirect_to('admin/categories');
        }

        $editId = (int) ($_GET['edit'] ?? 0);

        $this->render('admin', 'categories', 'Gestion des catégories', 'admin/categories', [
            'categories' => $this->fetchCategories(),
            'editingCategory' => $editId > 0 ? $this->findCategory($editId) : null,
        ]);
    }

    /** Retourne false si le nom existe déjà. */
    private function createCategory(string $name, string $description): bool
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
            $stmt->execute(['name' => $name, 'description' => $description]);

            return true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    private function updateCategory(int $id, string $name, string $description): bool
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
            $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
            $stmt = $this->db->prepare('UPDATE courses SET category = :name WHERE category_id = :id');
            $stmt->execute(['name' => $name, 'id' => $id]);
            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    private function deleteCategory(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
