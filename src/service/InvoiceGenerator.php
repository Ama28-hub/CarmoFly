<?php

namespace App\Service;

require_once __DIR__ . '/../lib/fpdf.php';

class InvoiceGenerator
{
    /**
     * Génère un PDF de facture en mémoire et retourne son contenu.
     * @param array $detail Détail de la réservation (client, destination, dates, voyageurs, consommations, totaux)
     * @param string $invoiceNumber Numéro de facture
     * @return string Contenu binaire du PDF
     */
    public function renderPdf(array $detail, string $invoiceNumber): string
    {
        // Données de base
        $nom        = htmlspecialchars($detail['nom']        ?? '');
        $prenom     = htmlspecialchars($detail['prenom']     ?? '');
        $pays       = htmlspecialchars($detail['pays']       ?? '');
        $aeroport   = htmlspecialchars($detail['aeroport']   ?? '');
        $dateDepart = htmlspecialchars($detail['date_depart'] ?? '');
        $dateRetour = htmlspecialchars($detail['date_retour'] ?? '');
        $voyageurs  = $detail['voyageurs']   ?? [];
        $consomms   = $detail['consommations'] ?? [];
        $poidsBagages = (float)($detail['poids_bagages'] ?? 0);
        $paymentMode  = (int)($detail['paiement_mode'] ?? 1);

        // Recalcul des suppléments
        $nbTravelers = array_sum(array_column($voyageurs, 'quantite'));
        $maxKg       = 25 * $nbTravelers;
        $excedent    = max(0, $poidsBagages - $maxKg);
        $surcharge   = round($excedent * 20, 2);
        $paymentMode = (int)($detail['paiement_mode'] ?? 1);
        // 1=Carte int.+30€, 2=Visa+25€, 3=Virement+20€
        if ($paymentMode === 1) {
            $paymentFee = 30.0;
        } elseif ($paymentMode === 2) {
            $paymentFee = 25.0;
        } else {
            $paymentFee = 20.0;
        }

        // Totaux
        $totalHt    = number_format((float)($detail['total_ht']  ?? 0), 2, ',', ' ');
        $tva        = number_format((float)($detail['tva']       ?? 0), 2, ',', ' ');
        $totalTtc   = number_format((float)($detail['total_ttc'] ?? 0), 2, ',', ' ');
        $surchargeFmt  = number_format($surcharge, 2, ',', ' ');
        $paymentFmt    = number_format($paymentFee, 2, ',', ' ');

        // Création du document PDF
        $pdf = new \FPDF();
        $pdf->AddPage();

        // Titre
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode("FACTURE n°$invoiceNumber"), 0, 1, 'C');
        $pdf->Ln(5);

        // En-tête client / destination
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 6, utf8_decode("Client : $nom $prenom"), 0, 1);
        $pdf->Cell(0, 6, utf8_decode("Destination : $pays ($aeroport)"), 0, 1);
        $pdf->Cell(0, 6, utf8_decode("Du $dateDepart au $dateRetour"), 0, 1);
        $pdf->Ln(10);

        // Tableau des voyageurs
        if (!empty($voyageurs)) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(80, 7, 'Type', 1);
            $pdf->Cell(30, 7, 'Quantité', 1, 0, 'C');
            $pdf->Cell(40, 7, '', 1, 1);
            $pdf->SetFont('Arial', '', 12);
            foreach ($voyageurs as $v) {
                $pdf->Cell(80, 6, utf8_decode($v['type_age'] ?? ''), 1);
                $pdf->Cell(30, 6, (int)utf8_decode($v['quantite'] ?? 0), 1, 0, 'C');
            }
            $pdf->Ln(5);
        }

        // Tableau des consommations
        if (!empty($consomms)) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(80, 7, 'Boisson', 1);
            $pdf->Cell(30, 7, 'Quantité', 1, 0, 'C');
            $pdf->Cell(40, 7, 'PU HT', 1, 0, 'R');
            $pdf->Cell(40, 7, 'Total HT', 1, 1, 'R');
            $pdf->SetFont('Arial', '', 12);
            foreach ($consomms as $c) {
                $pdf->Cell(80, 6, utf8_decode($c['label'] ?? ''), 1);
                $pdf->Cell(30, 6, (int)$c['quantite'], 1, 0, 'C');
                $pdf->Cell(40, 6, number_format($c['prix'], 2, ',', ' ') . ' €', 1, 0, 'R');
                $pdf->Cell(40, 6, number_format($c['prix'] * $c['quantite'], 2, ',', ' ') . ' €', 1, 1, 'R');
            }
            $pdf->Ln(5);
        }

        // Totaux
        $pdf->Cell(110);
        $pdf->Cell(40, 6, 'Total HT :', 0, 0);
        $pdf->Cell(40, 6, "$totalHt €", 0, 1, 'R');
        $pdf->Cell(110);
        $pdf->Cell(40, 6, 'TVA :', 0, 0);
        $pdf->Cell(40, 6, "$tva €", 0, 1, 'R');
        $pdf->Cell(110);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 6, 'Total TTC :', 0, 0);
        $pdf->Cell(40, 6, "$totalTtc €", 0, 1, 'R');

        return $pdf->Output('', 'S');
    }
}
?>