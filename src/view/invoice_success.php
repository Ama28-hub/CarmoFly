<?php
// view/invoice_success.php
// Affiche un message de confirmation et le lien vers la facture, plus un aperçu du PDF
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Confirmation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 2rem;
    }
    .success-box {
      max-width: 600px;
      margin: 1rem auto;
      padding: 1.5rem;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    .success-box h1 {
      color:rgb(138, 45, 116);
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
    }
    .success-box p {
      font-size: 1rem;
      color: #444;
      margin: 1rem 0;
    }
    .success-box a {
      display: inline-block;
      margin-top: 1rem;
      padding: 0.6rem 1.2rem;
      background:rgb(138, 45, 138);
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      font-weight: bold;
    }
    .success-box a:hover {
      background:rgb(106, 36, 70);
    }
    .invoice-preview {
      max-width: 600px;
      margin: 1.5rem auto;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .invoice-preview iframe {
      width: 100%;
      height: 500px;
      border: none;
    }
  </style>
</head>
<body>
  <div class="success-box">
    <h1>Réservation confirmée !</h1>
    <p>Votre réservation a bien été enregistrée </p>
  </div>
</body>
</html>
