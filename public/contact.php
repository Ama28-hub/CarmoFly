<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclusion des fichiers PHPMailer depuis src/lib
require __DIR__ . '/../src/lib/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../src/lib/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../src/lib/PHPMailer-master/src/SMTP.php';

$messageEnvoye = false;
$erreur = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $sujet   = trim($_POST['sujet'] ?? '(Sans sujet)');
    $message = trim($_POST['message'] ?? '');

    if (empty($nom) || empty($email) || empty($message)) {
        $erreur = "Tous les champs marqués * sont requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse e-mail est invalide.";
    } else {
        $mail = new PHPMailer(true);
        try {
            // Configuration serveur Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'adouamalaetitiacarmelle@gmail.com';              // ✅ Remplace par ton adresse Gmail
            $mail->Password   = 'ukes eobi clye umui';     // ✅ Mot de passe d'application Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Expéditeur et destinataire
            $mail->setFrom($email, $nom);
            $mail->addAddress('adouamalaetitiacarmelle@gmail.com'); // ✅ Même adresse ou autre destinataire

            // Contenu
            $mail->Subject = "Message de contact – $sujet";
            $mail->Body    = "Nom : $nom\nEmail : $email\n\n$message";

            $mail->send();
            $messageEnvoye = true;
        } catch (Exception $e) {
            $erreur = "Erreur lors de l’envoi : {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CarmoFly – Contact</title>
  <link rel="stylesheet" href="css/global.css">
  <style>
    .contact-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 6vw;
      background: var(--mid-grey);
    }
    .contact-wrapper h1 {
      color: var(--light);
      margin-bottom: 1.5rem;
      font-size: clamp(2rem, 4vw, 3rem);
    }
    .contact-form {
      width: 100%;
      max-width: 520px;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .contact-form input,
    .contact-form textarea {
      padding: 0.75rem 1rem;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
    }
    .contact-form input { background:#fff; }
    .contact-form textarea { background:#fff; min-height: 160px; resize: vertical; }
    .contact-form button { align-self: center; }

    .success, .errors {
      max-width: 520px;
      margin-bottom: 1.5rem;
      padding: 1rem;
      border-radius: 6px;
      font-size: 0.95rem;
      background: #e0ffe5;
      color: #155724;
      border-left: 4px solid #28a745;
    }
    .errors {
      background: #ffe5e5;
      color: #721c24;
      border-left-color: #f21763;
    }

    @media(max-width:600px){
      .contact-wrapper{padding:2rem 4vw;}
    }
  </style>
</head>
<body>

  <header class="header">
    <div class="logo">CarmoFly</div>
    <nav>
      <ul class="nav-links">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="destinations.php">Destinations</a></li>
        <li><a href="apropos.php">À propos</a></li>
        <li><a href="contact.php" class="active">Contact</a></li>
      </ul>
    </nav>
    <a href="reservation.php?action=form" class="btn-primary">Réserver</a>
  </header>

  <main class="contact-wrapper">
    <h1>Nous contacter</h1>

    <?php if ($messageEnvoye): ?>
      <div class="success">✅ Votre message a bien été envoyé. Merci !</div>
    <?php elseif (!empty($erreur)): ?>
      <div class="errors">⚠️ <?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form class="contact-form" method="post" action="">
      <input type="text" name="nom" placeholder="Nom *" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
      <input type="email" name="email" placeholder="Email *" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      <input type="text" name="sujet" placeholder="Sujet" value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">
      <textarea name="message" placeholder="Votre message *" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
      <button type="submit" class="btn-primary">Envoyer</button>
    </form>
  </main>

  <footer>
    <div>© 2025 CarmoFly Agence. Tous droits réservés.</div>
  </footer>

</body>
</html>
