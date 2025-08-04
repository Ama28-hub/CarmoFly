<?php 
// $detail et $summary fournis par le Controller
$detail  = (array)$detail;
$summary = (array)$summary + [
  'flight_ht'   => 0.00,
  'remise'      => 0.00,
  'surcharge'   => 0.00,
  'cabin'       => 0.00,
  'payment'     => 0.00,
  'meals'       => 0.00,
  'drinks'      => 0.00,
  'travellers'  => '',
  'subtotal_ht' => 0.00,
  'tva'         => 0.00,
  'total_ttc'   => (float)($detail['total_ttc'] ?? 0),
];
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Récapitulatif de la réservation</title>
  <style>
    .recap-container {
      max-width: 600px;
      margin: 2rem auto;
      padding: 1.5rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      background: #fafafa;
      font-family: Arial, sans-serif;
    }
    .recap-container h2 {
      margin-bottom: 1rem;
      font-size: 1.75rem;
      color: #333;
    }
    .recap-container ul {
      list-style-type: none;
      padding: 0;
      margin: 0 0 1rem 0;
    }
    .recap-container ul li {
      padding: 0.5rem 0;
      border-bottom: 1px solid #e0e0e0;
      font-size: 1rem;
      color: #444;
    }
    .recap-container ul li strong {
      color: #000;
    }
    .recap-container p {
      margin: 0.5rem 0;
      font-size: 1.1rem;
    }
    .recap-container p strong {
      font-size: 1.2rem;
      color: #000;
    }
    .recap-actions {
      margin-top: 1.5rem;
      text-align: right;
    }
    .recap-actions a,
    .recap-actions button {
      display: inline-block;
      margin-left: 0.5rem;
      padding: 0.6rem 1.2rem;
      border: none;
      border-radius: 4px;
      background: #6c5ce7;
      color: #fff;
      text-decoration: none;
      font-size: 0.95rem;
      cursor: pointer;
    }
    .recap-actions a:hover,
    .recap-actions button:hover {
      background: #5941b5;
    }
  </style>
</head>
<body>
  <div class="recap-container">
    <h2>Récapitulatif</h2>
    <ul>
      <li><strong>Voyageurs :</strong> <?= htmlspecialchars($summary['travellers']) ?></li>
      <li>Prix Billet (HT)            : <?= number_format($summary['flight_ht'] ?? 0, 2, ',', ' ') ?> €</li>
      <li>Total Remise (5 % ; 2 %)    : <?= number_format($summary['remise'],      2, ',', ' ') ?> €</li>
      <li>Surcharge bagages           : <?= number_format($summary['surcharge'],   2, ',', ' ') ?> €</li>
      <li>Bagages cabine              : <?= number_format($summary['cabin_fee'],       2, ',', ' ') ?> €</li>
      <li>Frais paiement              : <?= number_format($summary['payment_fee'],     2, ',', ' ') ?> €</li>
      <li>Options repas (HT)          : <?= number_format($summary['meals'],       2, ',', ' ') ?> €</li>
      <li>Options boissons (HT)       : <?= number_format($summary['drinks'],      2, ',', ' ') ?> €</li>
      <li><strong>Sous-total (HT)      : <?= number_format($summary['subtotal_ht'],2, ',', ' ') ?> €</strong></li>
    </ul>
    <p>TVA 21 % : <?= number_format($summary['tva'],       2, ',', ' ') ?> €</p>
    <p><strong>Total (TTC) : <?= number_format($summary['total_ttc'],2, ',', ' ') ?> €</strong></p>

    <div class="recap-actions">
      <form action="?action=finalize&amp;id=<?= (int)$detail['id'] ?>" method="post" style="display:inline">
        <button type="submit">Finaliser</button>
      </form>
    </div>
  </div>
</body>
</html>
