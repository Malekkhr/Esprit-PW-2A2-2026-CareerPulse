<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\Application;

final class ApplicationController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            flash('error', 'Connectez-vous pour voir vos candidatures.');
            redirect_to('login');
        }

        $this->render('front', 'applications', 'Mes candidatures', 'applications', [
            'applications' => Application::forUser((int) $_SESSION['user_id']),
        ]);
    }
}
