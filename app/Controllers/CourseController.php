<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\Course;

final class CourseController extends Controller
{
    public function index(): void
    {
        $this->render('front', 'courses', 'Formations', 'courses', ['courses' => Course::all()]);
    }
}
