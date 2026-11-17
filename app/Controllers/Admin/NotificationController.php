<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\Controller;
use CareerPulse\Models\Notification;

final class NotificationController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            Notification::markAllRead();
            flash('success', 'Les notifications sont marquées comme lues.');
            redirect_to('admin/notifications');
        }

        $this->render('admin', 'notifications', 'Notifications', 'admin/notifications', ['notifications' => Notification::all()]);
    }
}
