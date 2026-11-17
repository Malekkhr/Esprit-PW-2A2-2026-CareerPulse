<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\Controller;
use CareerPulse\Models\Application;

final class ApplicationController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $applicationId = (int) ($_POST['application_id'] ?? 0);
            $status = (string) ($_POST['status'] ?? '');
            if ($applicationId > 0 && in_array($status, ['Acceptée', 'Refusée'], true)) {
                Application::setStatus($applicationId, $status);
                flash('success', $status === 'Acceptée' ? 'La candidature a été acceptée.' : 'La candidature a été refusée.');
            }
            redirect_to('admin/applications');
        }

        $this->render('admin', 'applications', 'Candidatures', 'admin/applications', ['applications' => Application::all()]);
    }
}
