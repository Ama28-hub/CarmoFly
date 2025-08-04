<?php
namespace App\Controller;

use App\Model\ReservationModel;
use App\Service\InvoiceGenerator;
use App\Config\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ReservationController
{
    private ReservationModel $model;
    private InvoiceGenerator $invoiceGenerator;
    public array $errors    = [];
    public bool  $success   = false;
    public array $detail    = [];

    private const RESPONSABLE_EMAIL = 'adouamalaetitiacarmelle@gmail.com';

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->model            = new ReservationModel($pdo);
        $this->invoiceGenerator = new InvoiceGenerator();
    }

    public function form(): void
    {
        $destinations = $this->model->getDestinations();
        $meals        = $this->model->getMealChoices();
        $boissons     = $this->model->getBoissons();
        $tarifs       = $this->model->getTarifs();

        $defaults = [
            'nom'               => '',
            'prenom'            => '',
            'mail'              => '',
            'tel'               => '',
            'gsm'               => '',
            'cp'                => '',
            'adresse'           => '',
            'destination'       => '',
            'airport_choice'    => '',
            'nb_adultes'        => '0',
            'nb_enfants'        => '0',
            'nb_bebes'          => '0',
            'nb_cabin'          => '0',
            'bag_cabine_adult'  => '',
            'bag_cabine_enfant' => '',
            'bag_cabine_bebe'   => '',
            'date_depart'       => '',
            'date_retour'       => '',
            'paiement_mode'     => '',
            'poids_bagages'     => '0',
            'entree_id'         => '',
            'plat_id'           => '',
            'dessert_id'        => '',
        ];

        // Chargement des données (GET id pour modification, sinon POST ou vide)
        if (
            $_SERVER['REQUEST_METHOD'] === 'GET' &&
            isset($_GET['id'])
        ) {
            $id     = (int) $_GET['id'];
            $detail = $this->model->getReservationDetails($id);
            if (!$detail) {
                header('Location: reservation.php?action=form');
                exit;
            }
            $data = $defaults;
            // Map detail to data
            $data['nom']            = $detail['nom'];
            $data['prenom']         = $detail['prenom'];
            $data['mail']           = $detail['email_client'];
            $data['tel']            = $detail['telephone'];
            $data['gsm']            = $detail['gsm'];
            $data['cp']             = $detail['code_postal'];
            $data['adresse']        = $detail['adresse'] ?? '';
            $data['destination']    = $detail['destination_id'] . '|' . $detail['airport_choice'];
            $data['airport_choice'] = $detail['airport_choice'];
            $data['date_depart']    = $detail['date_depart'];
            $data['date_retour']    = $detail['date_retour'];
            $data['paiement_mode']  = $detail['paiement_mode'];
            $data['poids_bagages']  = $detail['poids_bagages'];
            $data['nb_adultes']     = $detail['nb_adultes'];
            $data['nb_enfants']     = $detail['nb_enfants'];
            $data['nb_bebes']       = $detail['nb_bebes'];
            // récupère nb_cabin passé en GET
            $data['nb_cabin']       = $_GET['nb_cabin'] ?? '0';
            $data['entree_id']      = $detail['entree_id']   ?? '';
            $data['plat_id']        = $detail['plat_id']     ?? '';
            $data['dessert_id']     = $detail['dessert_id']  ?? '';
            foreach ($boissons as $b) {
                $key = 'boisson_' . $b['id'];
                $data[$key] = 0;
                foreach ($detail['consommations'] ?? [] as $c) {
                    if ($c['label'] === $b['label']) {
                        $data[$key] = $c['quantite'];
                        break;
                    }
                }
            }
        } else {
            // POST ou premier accès
            $data = array_merge($defaults, $_POST);
        }

        // Traitement POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->errors = $this->validateData($data);
            // extraction destination/airport
            if (
                !empty($data['destination']) &&
                strpos($data['destination'], '|') !== false
            ) {
                [$destId, $airportNum]          = explode('|', $data['destination']);
                $data['destination_id']         = (int)$destId;
                $data['airport_choice']         = (int)$airportNum;
            } else {
                $this->errors['destination'] = 'Choix de destination invalide.';
            }
            // validation paiement mode
            if (!in_array($data['paiement_mode'], ['1','2','3'], true)) {
                $this->errors['paiement_mode'] = 'Mode de paiement invalide.';
            } else {
                $data['paiement_mode'] = (int)$data['paiement_mode'];
            }

            if (empty($this->errors)) {
                // Calcul des montants repas
                $mealsSum = 0;
                foreach (['entree_id','plat_id','dessert_id'] as $k) {
                    if (!empty($data[$k])) {
                        $type = substr($k, 0, -3);
                        foreach ($meals[$type] as $item) {
                            if ($item['id'] === (int)$data[$k]) {
                                $mealsSum += (float)$item['prix_unitaire'];
                            }
                        }
                    }
                }
                // boissons
                $drinksSum = 0;
                foreach ($boissons as $b) {
                    $key = 'boisson_' . $b['id'];
                    $qty = (int)($data[$key] ?? 0);
                    if ($qty > 0) {
                        $drinksSum += $qty * (float)$b['prix'];
                    }
                }
                // bagages cabine
                $cabinBags = 0;
                if (!empty($data['bag_cabine_adult']))  $cabinBags += (int)$data['nb_adultes'];
                if (!empty($data['bag_cabine_enfant'])) $cabinBags += (int)$data['nb_enfants'];
                if (!empty($data['bag_cabine_bebe']))   $cabinBags += (int)$data['nb_bebes'];

                // compute pricing
                $pricing = $this->computePricing(
                    $data['destination_id'],
                    $data['airport_choice'],
                    $data['date_depart'],
                    $data['date_retour'],
                    (int)$data['nb_adultes'],
                    (int)$data['nb_enfants'],
                    (int)$data['nb_bebes'],
                    (float)str_replace(',', '.', $data['poids_bagages']),
                    $data['paiement_mode'],
                    $cabinBags,
                    $mealsSum,
                    $drinksSum
                );
                $data = array_merge($data, $pricing);

                // sauvegarde
                $resaId = $this->model->save($data);
                $this->model->saveVoyageurs($resaId, $data);
                $this->model->saveConsommations($resaId, $data);
                $this->detail  = $this->model->getReservationDetails($resaId);
                $this->success = true;

                $this->sendConfirmationEmail($this->detail, $pricing, $resaId);
                session_start();
                $_SESSION['last_resa']    = $this->detail;
                $_SESSION['last_pricing'] = $pricing; 
                header('Location: reservation.php?action=recap&id=' . $resaId . '&nb_cabin=' . $cabinBags);
                exit;
            }
        }

        include __DIR__ . '/../view/reservation_detail.php';
    }

    public function recap(): void
    {
        if (!isset($_GET['id'])) {
            header('Location: reservation.php?action=form');
            exit;
        }

        $id      = (int) $_GET['id'];
        $detail  = $this->model->getReservationDetails($id);
        if (!$detail) {
            header('Location: reservation.php?action=form');
            exit;
        }

        // 1) On reconstitue le nombre de repas et boissons
        $choices  = $this->model->getMealChoices();
        $mealsSum = 0;
        foreach (['entree_id','plat_id','dessert_id'] as $k) {
            if (!empty($detail[$k])) {
                $type = substr($k, 0, -3);
                foreach ($choices[$type] as $item) {
                    if ($item['id'] === (int)$detail[$k]) {
                        $mealsSum += (float)$item['prix_unitaire'];
                        break;
                    }
                }
            }
        }

        $drinksSum = 0;
        foreach ($detail['consommations'] as $c) {
            $drinksSum += $c['quantite'] * (float)$c['prix'];
        }

        // 2) On retrouve le nombre de bagages cabine qu’on avait passé en GET
        $cabinBags = (int)($_GET['nb_cabin'] ?? 0);

        // 3) On recalcule TOUT via la même méthode qu’à l’envoi du mail
        $pricing = $this->computePricing(
            (int)$detail['destination_id'],
            (int)$detail['airport_choice'],
            $detail['date_depart'],
            $detail['date_retour'],
            (int)$detail['nb_adultes'],
            (int)$detail['nb_enfants'],
            (int)$detail['nb_bebes'],
            (float)$detail['poids_bagages'],
            (int)$detail['paiement_mode'],
            $cabinBags,
            $mealsSum,
            $drinksSum
        );

        // 4) On prépare le summary exactement avec les clés que votre vue attend
        $summary = [
            'travellers'  => sprintf(
                '%d adulte(s), %d enfant(s), %d bébé(s)',
                $detail['nb_adultes'],
                $detail['nb_enfants'],
                $detail['nb_bebes']
            ),
            'flight_ht'   => $pricing['flight_ht'],
            'remise'      => $pricing['remise'],
            'surcharge'   => $pricing['surcharge'],
            'cabin_fee'   => $pricing['cabin_fee'],
            'payment_fee' => $pricing['payment_fee'],
            'meals'       => $pricing['meals'],
            'drinks'      => $pricing['drinks'],
            'subtotal_ht' => $pricing['total_ht'],
            'tva'         => $pricing['tva'],
            'total_ttc'   => $pricing['total_ttc'],
        ];

        include __DIR__ . '/../view/recap_summary.php';
    }



    protected function validateData(array $data): array
    {
        $errors = [];
        // ... validation logic inchangée ...
        return $errors;
    }

    protected function computePricing(
        int    $destId,
        int    $airportChoice,
        string $dateDepart,
        string $dateRetour,
        int    $adultes,
        int    $enfants,
        int    $bebes,
        float  $poidsBagages,
        int    $paymentMode,
        int    $cabinBags = 0,
        float  $mealsSum  = 0.0,
        float  $drinksSum = 0.0
    ): array {
        // 1) Récupération des tarifs par tranche d’âge (doit inclure 'bebe')
        $t = $this->model->getTarifsByDestinationAirport($destId, $airportChoice);
        $flightHt = $adultes * $t['adulte']
                + $enfants * $t['enfant']
                + $bebes   * $t['bebe'];

        // 2) Bagages en soute : 20 kg gratuits / voyageur
        $freeKg   = 20 * ($adultes + $enfants + $bebes);
        $excedent = max(0, $poidsBagages - $freeKg);
        $surcharge = round($excedent * 25, 2);

        // 3) Bagages cabine : 1 x 10 € par bagage
        $cabinFee = round($cabinBags * 10, 2);

        // 4) Frais de paiement
        switch ($paymentMode) {
            case 1: $paymentFee = 30; break; // CB internationale
            case 2: $paymentFee = 25; break; // Visa
            case 3: $paymentFee = 20; break; // Virement
            default:
                throw new \InvalidArgumentException('Mode de paiement invalide');
        }

        // 5) Sous-total HT avant remises
        //    inclut vols + soute + cabine + paiement + repas + boissons
        $totalBefore = array_sum([
            $flightHt,
            $surcharge,
            $cabinFee,
            $paymentFee,
            $mealsSum,
            $drinksSum
        ]);

        // 6) Calcul des remises cumulées
        $now        = new \DateTime();
        $dtDepart   = new \DateTime($dateDepart);
        $dtRetour   = new \DateTime($dateRetour);

        // 6a) 5 % si réservation >= 2 mois avant le départ
        $monthsDiff = ($now->diff($dtDepart)->y * 12)
                    + $now->diff($dtDepart)->m;
        $earlyRem   = ($monthsDiff >= 2)
            ? round(0.05 * $totalBefore, 2)
            : 0.0;

        // 6b) 2 % si durée du séjour < 1 mois
        $stayDays   = $dtDepart->diff($dtRetour)->days;
        $shortRem   = ($stayDays < 30)
            ? round(0.02 * $totalBefore, 2)
            : 0.0;

        $remise = $earlyRem + $shortRem;

        // 7) Calcul final HT, TVA et TTC
        $netHt    = round($totalBefore - $remise, 2);
        $tva      = round($netHt * 0.21, 2);
        $totalTtc = round($netHt + $tva, 2);

        return [
            'flight_ht'   => round($flightHt, 2),
            'surcharge'   => $surcharge,
            'cabin_fee'   => $cabinFee,
            'payment_fee' => $paymentFee,
            'meals'       => round($mealsSum, 2),
            'drinks'      => round($drinksSum, 2),
            'remise'      => $remise,
            'total_ht'    => $netHt,
            'tva'         => $tva,
            'total_ttc'   => $totalTtc,
        ];
    }


    
    protected function sendConfirmationEmail(array $detail, array $pricing, int $resaId): void
    {
        require_once __DIR__ . '/../lib/PHPMailer-master/src/Exception.php';
        require_once __DIR__ . '/../lib/PHPMailer-master/src/PHPMailer.php';
        require_once __DIR__ . '/../lib/PHPMailer-master/src/SMTP.php';

        $mail = new PHPMailer(true);
        try {
            // SMTP GMAIL
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'adouamalaetitiacarmelle@gmail.com';
            $mail->Password   = 'ukes eobi clye umui';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('no-reply@votre-domaine.com', 'CarmoFly');
            $mail->addAddress(self::RESPONSABLE_EMAIL);
            $mail->addReplyTo($detail['email_client'], $detail['prenom'] . ' ' . $detail['nom']);

            $mail->isHTML(true);
            $mail->Subject = "[Réservation #{$resaId}] Nouvelle réservation";
            $body  = '<h1>Nouvelle réservation #' . $resaId . '</h1>';
            $body .= '<p><strong>Client :</strong> ' . htmlspecialchars($detail['prenom'] . ' ' . $detail['nom']) . '</p>';
            $body .= '<p><strong>Destination :</strong> ' . htmlspecialchars($detail['pays'] . ' (' . $detail['aeroport'] . ')') . '</p>';
            $body .= '<p><strong>Dates :</strong> ' . htmlspecialchars($detail['date_depart'] . ' au ' . $detail['date_retour']) . '</p>';
            $body .= '<h2>Détails Reservation</h2>';
            $body .= '<ul>';
            $body .= '<li>Prix billet: ' . number_format($pricing['flight_ht'], 2, ',', ' ') . ' €</li>';
            $body .= '<li> Remise : ' . number_format($pricing['remise'], 2, ',', ' ') . ' €</li>';
            $body .= '<li> Surcharge bagages : ' . number_format($pricing['surcharge'], 2, ',', ' ') . ' €</li>';
            $body .= '<li> Bagage cabine : ' . number_format($pricing['cabin_fee'], 2, ',', ' ') . ' €</li>';
            $body .= '<li> Frais paiement : ' . number_format($pricing['payment_fee'], 2, ',', ' ') . ' €</li>';
            $body .= '<li> Repas (HT) : ' . number_format($pricing['meals'], 2, ',', ' ') . ' €</li>';
            $body .= '<li> Boissons (HT) : ' . number_format($pricing['drinks'], 2, ',', ' ') . ' €</li>';
            $body .= '<li><strong>Sous-total HT : ' . number_format($pricing['total_ht'], 2, ',', ' ') . ' €</strong></li>';
            $body .= '</ul>';
            $body .= '<p><strong>TVA (21 %)</strong> : ' . number_format($pricing['tva'], 2, ',', ' ') . ' €</p>';
            $body .= '<p><strong>Total TTC : ' . number_format($pricing['total_ttc'], 2, ',', ' ') . ' €</strong></p>';

            $mail->Body = $body;
            $mail->AltBody = strip_tags(str_replace(['<br>', '</li>', '<li>'], "\n", $body));

            $mail->send();
        } catch (Exception $e) {
            error_log('Mail error: ' . $e->getMessage());
        }
    }

    public function finalize(int $id): void
    {
        $this->model->setStatus($id, 'final');
        $existing = $this->model->getInvoiceByReservation($id);
        if ($existing) {
            $invoiceUrl = '/' . $existing['pdf_path'];
            include __DIR__ . '/../view/invoice_success.php';
            exit;
        }
        $detail = $this->model->getReservationDetails($id);
        if (!$detail) {
            header('Location: reservation.php?action=form');
            exit;
        }
        $invoiceNumber = date('Ymd') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
        $pdfContent    = $this->invoiceGenerator->renderPdf($detail, $invoiceNumber);
        $outputDir     = __DIR__ . '/../../public/invoices/';
        if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);
        $fileName      = $invoiceNumber . '.pdf';
        file_put_contents($outputDir . $fileName, $pdfContent);
        $this->model->saveInvoice(
            $id,
            $invoiceNumber,
            'invoices/' . $fileName,
            (float)$detail['total_ht'],
            (float)$detail['tva'],
            (float)$detail['total_ttc']
        );
        $invoiceUrl = '/invoices/' . $fileName;
        include __DIR__ . '/../view/invoice_success.php';
        exit;
    }
}

