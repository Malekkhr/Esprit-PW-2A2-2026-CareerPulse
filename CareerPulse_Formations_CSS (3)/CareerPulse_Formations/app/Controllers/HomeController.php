<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

final class HomeController extends DatabaseController
{
    use CatalogQueries;

    public function index(): void
    {
        $this->render('front', 'home', 'Accueil', 'home', [
            'courses' => array_slice($this->fetchCourses(true), 0, 3),
            'categories' => $this->fetchCategories(),
        ]);
    }
}
