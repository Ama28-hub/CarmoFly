<?php
require __DIR__ . '/autoload.php';
use App\Config\Database;
use App\Model\ReservationModel;

$pdo = Database::getConnection();
$model = new ReservationModel($pdo);
$destinations = $model->getDestinations();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CarmoFly – Destinations</title>
  <link rel="stylesheet" href="css/global.css">
</head>
<body>

<header class="header">
  <div class="logo">CarmoFly</div>
  <nav>
    <ul class="nav-links">
      <li><a href="index.php" class="active">Accueil</a></li>
      <li><a href="destinations.php">Destinations</a></li>
      <li><a href="apropos.php">À propos</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
  <a href="reservation.php?action=form" class="btn-primary">Réserver</a>
</header>

<main class="container">
  <h2>Nos destinations</h2>
  <div class="destinations-grid">
    <?php foreach ($destinations as $id => $info): ?>
      <?php
        // Nettoyer le nom du pays pour créer le nom du fichier image
        $imageName = strtolower(strtr($info['pays'], [
          ' ' => '-', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
          'à' => 'a', 'â' => 'a', 'î' => 'i', 'ï' => 'i', 'ô' => 'o',
          'û' => 'u', 'ù' => 'u', 'ç' => 'c'
        ])) . '.jpg';
        $imagePath = "images/destinations/$imageName";
      ?>
      <div class="card destination-card">
        <div class="image-wrapper">
          <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($info['pays']) ?>">
        </div>
        <h3><?= htmlspecialchars($info['pays']) ?></h3>
        <p class="aero-price">À partir de <?= htmlspecialchars($info['prix_min']) ?> €</p>
        <ul>
          <?php foreach ($info['aeroports'] as $num => $nom): ?>
            <li>
              <?= htmlspecialchars($nom) ?>
              <a href="reservation.php?action=form&destination=<?= $id ?>|<?= $num ?>" class="btn-primary">Réserver</a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<footer>
  <div>© 2025 CarmoFly Agence. Tous droits réservés.</div>
</footer>

</body>
</html>
