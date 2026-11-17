<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\Course;
use CareerPulse\Models\Offer;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('front', 'home', 'Accueil', 'home', [
            'offers' => array_slice(Offer::visibleToStudents(), 0, 3),
            'courses' => Course::all(),
        ]);
    }
}
