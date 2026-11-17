<?php
declare(strict_types=1);

namespace CareerPulse\Controllers;

use CareerPulse\Models\Application;
use CareerPulse\Models\Offer;

final class OfferController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $this->apply();
        }

        $type = (string) ($_GET['type'] ?? '');
        $this->render('front', 'offers', 'Opportunités', 'offers', [
            'offers' => Offer::visibleToStudents($type),
            'type' => $type,
        ]);
    }

    private function apply(): never
    {
        $offer = Offer::findApplicable((string) ($_POST['offer_id'] ?? ''));

        if ($offer !== null) {
            if (empty($_SESSION['user_id'])) {
                flash('error', 'Connectez-vous pour envoyer une candidature.');
                redirect_to('login');
            }
            if (Application::create((int) $_SESSION['user_id'], $offer['id'])) {
                flash('success', 'Votre candidature a été enregistrée.');
            } else {
                flash('info', 'Cette offre figure déjà dans vos candidatures.');
            }
            redirect_to('applications');
        }

        flash('error', 'Cette offre ne peut pas recevoir de candidature.');
        redirect_to('offers');
    }
}
