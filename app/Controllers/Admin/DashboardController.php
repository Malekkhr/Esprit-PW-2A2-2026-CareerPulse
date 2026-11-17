<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\Controller;
use CareerPulse\Models\Application;
use CareerPulse\Models\Offer;
use CareerPulse\Models\Report;
use CareerPulse\Models\User;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $offers = Offer::all();
        $pending = count(array_filter($offers, static fn (array $o): bool => $o['status'] === 'À examiner'));

        $this->render('admin', 'dashboard', 'Vue d’ensemble', 'admin/dashboard', [
            'pending' => $pending,
            'offerCount' => count($offers),
            'users' => $this->safeCount(static fn (): int => count(User::all())),
            'reports' => $this->safeCount(static fn (): int => Report::countOpen()),
            'pendingApplications' => $this->safeCount(static fn (): int => Application::countPending()),
            'offers' => array_slice($offers, 0, 4),
        ]);
    }

    private function safeCount(callable $counter): int
    {
        try {
            return $counter();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
