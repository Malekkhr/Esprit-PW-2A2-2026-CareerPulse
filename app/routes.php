<?php
declare(strict_types=1);

use CareerPulse\Controllers\Admin;
use CareerPulse\Controllers\ApplicationController;
use CareerPulse\Controllers\CourseController;
use CareerPulse\Controllers\HomeController;
use CareerPulse\Controllers\LoginController;
use CareerPulse\Controllers\OfferController;
use CareerPulse\Controllers\ProfileController;
use CareerPulse\Controllers\RegisterController;

/**
 * Table de routage : ?page=<clé> => [Contrôleur, méthode].
 * Un contrôleur par page, comme dans l'atelier.
 */
return [
    // Front-office
    'home'         => [HomeController::class, 'index'],
    'offers'       => [OfferController::class, 'index'],
    'courses'      => [CourseController::class, 'index'],
    'profile'      => [ProfileController::class, 'index'],
    'applications' => [ApplicationController::class, 'index'],
    'register'     => [RegisterController::class, 'index'],
    'login'        => [LoginController::class, 'index'],
    'logout'       => [LoginController::class, 'logout'],

    // Back-office
    'admin/dashboard'     => [Admin\DashboardController::class, 'index'],
    'admin/offers'        => [Admin\OfferController::class, 'index'],
    'admin/applications'  => [Admin\ApplicationController::class, 'index'],
    'admin/users'         => [Admin\UserController::class, 'index'],
    'admin/reports'       => [Admin\ReportController::class, 'index'],
    'admin/notifications' => [Admin\NotificationController::class, 'index'],
];
