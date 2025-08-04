<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Facture <?= htmlspecialchars($invoiceNumber, ENT_QUOTES, 'UTF-8') ?></title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { padding: 5px; border: 1px solid #ccc; }
    th { background: #f0f0f0; text-align: left; }
    .totals td { border: none; }
    .totals tr td:first-child { text-align: right; padding-right: 10px; }
  </style>
</head>
<body>
  <div class="header">
    <h1>FACTURE n° <?= htmlspecialchars($invoiceNumber, ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Date : <?= date('d/m/Y') ?></p>
  </div>

  <table class="client">
    <tr>
      <td>
        <strong>Client :</strong><br>
        <?= htmlspecialchars($detail['nom'] . ' ' . $detail['prenom'], ENT_QUOTES, 'UTF-8') ?><br>
        <?= htmlspecialchars($detail['email_client'], ENT_QUOTES, 'UTF-8') ?><br>
        Tél. <?= htmlspecialchars($detail['telephone'], ENT_QUOTES, 'UTF-8') ?>
      </td>
      <td>
        <strong>Destination :</strong><br>
        <?= htmlspecialchars($detail['pays'] . ' (' . $detail['aeroport'] . ')', ENT_QUOTES, 'UTF-8') ?><br>
        Du <?= (new DateTime($detail['date_depart']))->format('d/m/Y') ?> au <?= (new DateTime($detail['date_retour']))->format('d/m/Y') ?>
      </td>
    </tr>
  </table>

  <h3>Voyageurs & bagages</h3>
  <table class="details">
    <tr><th>Tranche</th><th>Quantité</th><th>Poids (kg)</th></tr>
    <?php foreach ($detail['voyageurs'] as $v): ?>
      <tr>
        <td><?= htmlspecialchars($v['type_age'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= (int)$v['quantite'] ?></td>
        <td><?= (int)$v['poids_kg'] ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <?php if (!empty($detail['consommations'])): ?>
    <h3>Consommations</h3>
    <table class="details">
      <tr><th>Boisson</th><th>Qté</th><th>PU HT</th><th>Total HT</th></tr>
      <?php foreach ($detail['consommations'] as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['label'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= (int)$c['quantite'] ?></td>
        <td><?= number_format($c['prix'], 2, ',', ' ') ?> €</td>
        <td><?= number_format($c['quantite'] * $c['prix'], 2, ',', ' ') ?> €</td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>

  <?php
    // Calcul des suppléments
    $nbTravelers = array_sum(array_column($detail['voyageurs'], 'quantite'));
    $poidsBagages = (float)$detail['poids_bagages'];
    $excedent = max(0, $poidsBagages - 25 * $nbTravelers);
    $surcharge = number_format($excedent * 20, 2, ',', ' ');
    $paymentFee = ((int)$detail['paiement_mode'] === 1) ? '30,00' : '20,00';
  ?>

  <h3>Suppléments</h3>
  <table class="details">
    <tr><td>Surcharge bagages</td><td><?= $surcharge ?> €</td></tr>
    <tr><td>Frais paiement</td><td><?= $paymentFee ?> €</td></tr>
  </table>

  <h3>Résumé des montants</h3>
  <table class="totals">
    <tr><td><strong>Total HT :</strong></td><td><?= number_format($detail['total_ht'], 2, ',', ' ') ?> €</td></tr>
    <tr><td><strong>TVA :</strong></td><td><?= number_format($detail['tva'], 2, ',', ' ') ?> €</td></tr>
    <tr><td><strong>Total TTC :</strong></td><td><strong><?= number_format($detail['total_ttc'], 2, ',', ' ') ?> €</strong></td></tr>
  </table>

  <p>Merci pour votre confiance !</p>
</body>
</html>
