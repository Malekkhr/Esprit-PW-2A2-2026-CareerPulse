<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

abstract class Controller
{
    /**
     * Rend une vue de contenu (Views/{area}/pages/{view}.php) dans le layout commun.
     *
     * @param string      $area         'front' ou 'admin'
     * @param string      $view         nom de la vue (sans .php)
     * @param string      $title        titre de l'onglet
     * @param string      $currentPage  clé de la page courante (lien actif)
     * @param array       $data         variables extraites dans la vue
     * @param string|null $scriptsView  vue de scripts spécifiques (sans .php), optionnelle
     */
    protected function render(string $area, string $view, string $title, string $currentPage, array $data = [], ?string $scriptsView = null): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require __DIR__ . '/../Views/' . $area . '/pages/' . $view . '.php';
        $content = (string) ob_get_clean();

        $extraScripts = '';
        if ($scriptsView !== null) {
            ob_start();
            require __DIR__ . '/../Views/' . $area . '/pages/' . $scriptsView . '.php';
            $extraScripts = (string) ob_get_clean();
        }

        require __DIR__ . '/../Views/' . $area . '/layout.php';
    }

    protected function postIsValid(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        if (!verify_csrf()) {
            flash('error', 'La session du formulaire a expiré. Réessayez.');
            return false;
        }

        return true;
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('front', 'not-found', 'Page introuvable', '');
    }
}
