<?php
  $errors       = $errors    ?? [];
  $success      = $success   ?? false;
  $destinations = $destinations ?? [];
  $meals        = $meals     ?? [];
  $boissons     = $boissons  ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réservation</title>
  <link rel="stylesheet" href="/Projet1/public/css/reservation.css">
  <script src="/Projet1/public/js/reservation.js" defer></script>
</head>
<body>
  <!-- Logo CarmoFly -->
    <div class="logo-container" style="text-align:center; margin:2rem 0;">
      <img src="../public/images/logo_carmofly.png" alt="CarmoFly" style="max-height:80px;">
    </div>
  <h1>Réservation de voyage</h1>

  <?php if (!empty($errors)): ?>
    <div class="errors">
      <ul>
        <?php foreach ($errors as $msg): ?>
          <li><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (!$success): ?>
    
    <form class="reservation-form" method="post" action="?action=form">
      
      <fieldset>
        <legend>Informations client</legend>

        <label>Nom *
          <input
            type="text" name="nom" required maxlength="40"
            pattern="^[A-Za-zÀ-ÖØ-öø-ÿ'\s\-]{1,40}$"
            title="1 à 40 lettres, espaces, tirets ou apostrophes"
            value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
          >
        </label>

        <label>Prénom *
          <input
            type="text" name="prenom" required maxlength="40"
            pattern="^[A-Za-zÀ-ÖØ-öø-ÿ'\s\-]{1,40}$"
            title="1 à 40 lettres, espaces, tirets ou apostrophes"
            value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
          >
        </label>

        <label>E-mail *
          <input type="email" name="mail" required
            pattern="^[^@\s]+@[^@\s]+\.[^@\s]{3,}$"
            title="Exemple : nom@exemple.com (au moins 3 caractères après le point)"
            value="<?= htmlspecialchars($_POST['mail'] ?? '') ?>"
          >

        </label>

        <label>Téléphone
          <input
            type="text" name="tel" required maxlength="15"
            pattern="^\d{6,15}$"
            title="6 à 15 chiffres"
            value="<?= htmlspecialchars($_POST['tel'] ?? '') ?>"
          >
        </label>

        <label>GSM *
          <input
            type="text" name="gsm" required maxlength="10"
            pattern="^\d{1,10}$"
            title="1 à 10 chiffres"
            value="<?= htmlspecialchars($_POST['gsm'] ?? '') ?>"
          >
        </label>

        <label>Code postal *
          <input
            type="text" name="cp" id= "cp" required maxlength="10"
            pattern="^\d{1,10}$"
            title="1 à 10 chiffres"
            value="<?= htmlspecialchars($_POST['cp'] ?? '') ?>"
          >
        </label>

        <label>Adresse *
          <textarea
            name="adresse" maxlength="70"
          ><?= htmlspecialchars($_POST['adresse'] ?? '') ?></textarea>
        </label>
      </fieldset>


      <fieldset>
        <legend>Voyage</legend>

        <label>Date départ *
          <input type="date" id="date_depart" name="date_depart" required
            min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
            value="<?= htmlspecialchars($_POST['date_depart'] ?? '') ?>">
        </label>

        <label>Date retour *
          <input type="date" id="date_retour" name="date_retour" required
            value="<?= htmlspecialchars($_POST['date_retour'] ?? '') ?>">
        </label>

        <label>Destination *
          <select id="destination" name="destination" required>
            <option value="">-- Choisissez --</option>
            <?php foreach ($destinations as $id => $dest): ?>
              <?php foreach ($dest['aeroports'] as $num => $nomA): 
                $val = "$id|$num"; ?>
                <option value="<?= htmlspecialchars($val) ?>"
                  data-price="<?= $dest['prix_min'] ?>"
                  <?= (($_POST['destination'] ?? '') === $val) ? 'selected' : '' ?>>
                  <?= htmlspecialchars(
                    "{$dest['pays']} – {$nomA} (à partir de " .
                    number_format($dest['prix_min'],2,',',' ') . " €)"
                  )?>
                </option>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </select>
        </label>

        <!-- Groupe voyageurs avec bagage à main individuel -->
        <div data-type="adult">
          <label>Adultes *
            <input id="nb_adultes" type="number" name="nb_adultes" required min="0" step="1"
              value="<?= htmlspecialchars($_POST['nb_adultes'] ?? '0') ?>">
          </label>
          <label class="baggage-field">
            <input type="checkbox" name="bag_cabine_adult"
              <?= isset($_POST['bag_cabine_adult']) ? 'checked' : '' ?>>
            Ajouter un bagage à main adulte (10 €)
          </label>
        </div>

        <div data-type="child">
          <label>Enfants *
            <input id="nb_enfants" type="number" name="nb_enfants" required min="0" step="1"
              value="<?= htmlspecialchars($_POST['nb_enfants'] ?? '0') ?>">
          </label>
          <label class="baggage-field">
            <input type="checkbox" name="bag_cabine_enfant"
              <?= isset($_POST['bag_cabine_enfant']) ? 'checked' : '' ?>>
            Ajouter un bagage à main enfant (10 €)
          </label>
        </div>

        <div data-type="baby">
          <label>Bébés *
            <input id="nb_bebes" type="number" name="nb_bebes" required min="0" step="1"
              value="<?= htmlspecialchars($_POST['nb_bebes'] ?? '0') ?>">
          </label>
          <label class="baggage-field">
            <input type="checkbox" name="bag_cabine_bebe"
              <?= isset($_POST['bag_cabine_bebe']) ? 'checked' : '' ?>>
            Ajouter un bagage à main bébé (10 €)
          </label>
       </div>
      </fieldset>
      <fieldset>
        <label>Poids bagages (kg) *
          <input id="poids_bagages" type="number" name="poids_bagages" required min="0" step="0.1"
            value="<?= htmlspecialchars($_POST['poids_bagages'] ?? '0') ?>">
        </label>
      </fieldset>

      <fieldset>
        <legend>Options repas &amp; boissons</legend>
        <p style="font-style: italic; font-size: 0.9em; color: #666;">
          Les bébés (moins de 2 ans) ne reçoivent pas de repas.
        </p>

        <?php foreach (['entree','plat','dessert'] as $type): ?>
          <label><?= ucfirst($type) ?>
            <select id="<?= $type ?>_id" name="<?= $type ?>_id">
              <option value="">Aucun</option>
              <?php foreach ($meals[$type] ?? [] as $m): ?>
                <?php $sel = (($_POST[$type.'_id'] ?? '') == $m['id']) ? 'selected' : ''; ?>
                <option value="<?= $m['id'] ?>" data-price="<?= $m['prix_unitaire'] ?>" <?= $sel ?>>
                  <?= htmlspecialchars("{$m['label']} (" . number_format($m['prix_unitaire'],2,',',' ') . " €)") ?>
                </option>
              <?php endforeach; ?>
            </select>
          </label>
        <?php endforeach; ?>

        <div class="full-width">
          <p>Boissons (quantité)</p>
          <?php foreach ($boissons as $b): ?>
            <label>
              <input type="number" name="boisson_<?= $b['id'] ?>" min="0" step="1"
                data-price="<?= $b['prix'] ?>"
                value="<?= (int)($_POST["boisson_{$b['id']}"] ?? 0) ?>">
              <?= htmlspecialchars("{$b['label']} (" . number_format($b['prix'],2,',',' ') . " €)") ?>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <fieldset>
        <legend>Paiement</legend>
        <label>
          <input type="radio" name="paiement_mode" value="1" required
            <?= (($_POST['paiement_mode'] ?? '')==='1')?'checked':'' ?>>
            Carte bancaire internationale (+30 €)
        </label>
        <label>
          <input type="radio" name="paiement_mode" value="2"
            <?= (($_POST['paiement_mode'] ?? '')==='2')?'checked':'' ?>>
          Carte Visa (+25 €)
        </label>
        <label>
          <input type="radio" name="paiement_mode" value="3"
            <?= (($_POST['paiement_mode'] ?? '')==='3')?'checked':'' ?>>
          Virement bancaire (+20 €)
        </label>
      </fieldset>

      <button id="btn-review" type="submit">Valider</button>
    </form>

  <?php endif; ?>
</body>
</html>
