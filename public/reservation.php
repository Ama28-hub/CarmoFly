<?php
// public/reservation.php
require __DIR__ . '/autoload.php';

use App\Controller\ReservationController;

// Instanciation du contrôleur
$controller = new ReservationController();

// Détermination de l'action
$action = $_GET['action'] ?? 'form';

switch ($action) {
    case 'form':
    case 'formulaire':
        $controller->form();
        break;
    case 'recap':
        $controller->recap();
        break;
    case 'finalize':
        // Vérification de l'ID
        if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
            $controller->finalize((int) $_GET['id']);
        } else {
            header('Location: reservation.php?action=form');
        }
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        echo 'Action "' . htmlspecialchars($action) . '" non reconnue.';
        break;
}
