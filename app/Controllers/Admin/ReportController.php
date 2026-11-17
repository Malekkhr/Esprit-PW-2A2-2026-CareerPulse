<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\Controller;
use CareerPulse\Models\Report;

final class ReportController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $reportId = (string) ($_POST['report_id'] ?? '');
            if ($reportId !== '') {
                Report::resolve($reportId);
                flash('success', 'Le signalement a été marqué comme traité.');
            }
            redirect_to('admin/reports');
        }

        $this->render('admin', 'reports', 'Signalements', 'admin/reports', ['reports' => Report::all()]);
    }
}
