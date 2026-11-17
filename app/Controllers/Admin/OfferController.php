<?php
declare(strict_types=1);

namespace CareerPulse\Controllers\Admin;

use CareerPulse\Controllers\Controller;
use CareerPulse\Models\Offer;

final class OfferController extends Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->postIsValid()) {
            $offerId = (string) ($_POST['offer_id'] ?? '');
            $status = (string) ($_POST['status'] ?? '');
            if (Offer::exists($offerId) && in_array($status, ['Validée', 'Rejetée'], true)) {
                Offer::setStatus($offerId, $status);
                flash('success', 'Le statut de l’offre a été mis à jour.');
            }
            redirect_to('admin/offers');
        }

        $this->render('admin', 'offers', 'Modération des offres', 'admin/offers', ['offers' => Offer::all()]);
    }
}
