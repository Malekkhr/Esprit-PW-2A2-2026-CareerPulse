<?php
declare(strict_types=1);

use CareerPulse\Controllers\Admin;
use CareerPulse\Controllers\AuthController;
use CareerPulse\Controllers\CourseController;
use CareerPulse\Controllers\HomeController;
use CareerPulse\Controllers\ProfileController;

/**
 * Table de routage : index.php?action=<clé> => [Contrôleur, méthode].
 */
return [
    // ---------------- FRONTOFFICE ----------------
    // Public
    'home'     => [HomeController::class, 'index'],
    'courses'  => [CourseController::class, 'index'],
    'register' => [AuthController::class, 'registerPage'],
    'login'    => [AuthController::class, 'loginPage'],
    // Réservé aux utilisateurs connectés (requireAuth)
    'logout'    => [AuthController::class, 'logout'],
    'dashboard' => [AuthController::class, 'dashboard'],
    'profile'   => [ProfileController::class, 'index'],

    // ---------------- BACKOFFICE (rôle admin uniquement : requireRole('admin')) ----------------
    'admin'            => [Admin\DashboardController::class, 'index'],
    'admin/dashboard'  => [Admin\DashboardController::class, 'index'],
    'admin/courses'    => [Admin\CourseController::class, 'index'],
    'admin/categories' => [Admin\CategoryController::class, 'index'],
    'admin/users'      => [Admin\UserController::class, 'index'],
];
