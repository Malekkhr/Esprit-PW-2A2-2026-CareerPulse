<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

/** Pages d'erreur : 404 (notFound(), hérité) et 403 (accès refusé). */
final class ErrorController extends Controller
{
    public function forbidden(): void
    {
        http_response_code(403);
        $this->render('front', 'forbidden', 'Accès refusé', '');
    }
}
