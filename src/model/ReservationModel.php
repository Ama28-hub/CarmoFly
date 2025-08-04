<?php
namespace App\Model;

use PDO;

class ReservationModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getDestinations(): array
    {
        $sql = "SELECT d.id, d.pays, d.nom_aeroport1, d.nom_aeroport2,
                    LEAST(t.prix_aero1, t.prix_aero2) AS prix_min
                FROM destinations d
                JOIN tarifs t ON t.destination_id = d.id
                WHERE t.tranche_age = '12+'
                ORDER BY d.pays";
        $stmt = $this->pdo->query($sql);
        $out = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $aeroports = [];
            if (!empty($r['nom_aeroport1'])) {
                $aeroports[1] = $r['nom_aeroport1'];
            }
            if (!empty($r['nom_aeroport2'])) {
                $aeroports[2] = $r['nom_aeroport2'];
            }
            $out[$r['id']] = [
                'pays'      => $r['pays'],
                'aeroports' => $aeroports,
                'prix_min'  => $r['prix_min'],
            ];
        }
        return $out;
    }

    public function getMealChoices(): array
    {
        $sql = "SELECT id, type, label, prix_unitaire
                FROM meals_choices
                ORDER BY FIELD(type,'entree','plat','dessert'), label";
        $stmt = $this->pdo->query($sql);
        $out = ['entree'=>[], 'plat'=>[], 'dessert'=>[]];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $out[$r['type']][] = [
                'id'            => (int)$r['id'],
                'label'         => $r['label'],
                'prix_unitaire' => (float)$r['prix_unitaire'],
            ];
        }
        return $out;
    }

    public function getBoissons(): array
    {
        $sql = "SELECT id, code, label, prix FROM boissons ORDER BY label";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvoiceByReservation(int $reservationId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, numero_facture, pdf_path 
            FROM factures 
            WHERE reservation_id = ?"
        );
        $stmt->execute([$reservationId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    /**
     * Sauvegarde de la réservation
     */
       
    public function save(array $d): int 
    {
        $sql = "INSERT INTO reservations
                (
                    nom,
                    prenom,
                    email_client,
                    telephone,
                    gsm,
                    code_postal,
                    adresse,
                    destination_id,
                    airport_choice,
                    date_depart,
                    date_retour,
                    entree_id,
                    plat_id,
                    dessert_id,
                    paiement_mode,
                    poids_bagages,
                    bag_cabine_adult,
                    bag_cabine_enfant,
                    bag_cabine_bebe,
                    nb_cabin,
                    total_ht,
                    tva,
                    total_ttc
                )
                VALUES (
                    ?,?,?,?,?,?,?,?,?,?,
                    ?,?,?,?,?,
                    ?,?,?,?,?,?,
                    ?,?
                )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            // données client
            $d['nom'],
            $d['prenom'],
            $d['mail'],
            $d['tel'],
            $d['gsm'],
            $d['cp'],
            $d['adresse'] ?? null,

            // voyage
            $d['destination_id'],
            $d['airport_choice'],
            $d['date_depart'],
            $d['date_retour'],

            // repas
            $d['entree_id']   ?? null,
            $d['plat_id']     ?? null,
            $d['dessert_id']  ?? null,

            // paiement & bagages soute
            $d['paiement_mode'],
            $d['poids_bagages'],

            // bagages cabine : flags
            $d['bag_cabine_adult']   ?? 0,
            $d['bag_cabine_enfant']  ?? 0,
            $d['bag_cabine_bebe']    ?? 0,

            // nombre total de sacs cabine (optionnel si vous enregistrez juste les flags)
            $d['nb_cabin'],

            // montants
            $d['total_ht'],
            $d['tva'],
            $d['total_ttc'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }


        /**
         * Sauvegarde des voyageurs sans stockage erroné du poids (poids global en réservation)
         */
    public function saveVoyageurs(int $resaId, array $d): void
        {
            $sql = "INSERT INTO voyageurs (reservation_id, type_age, quantite, poids_kg)
                    VALUES (?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);

            // on mappe 'bebes' sur 'baby' (valeur ENUM valide), et non plus 'bebe'
            $types = [
                'bebes'   => 'baby',
                'enfants' => 'child',
                'adultes' => 'adult'
            ];

            foreach ($types as $key => $type) {
                $stmt->execute([
                    $resaId,
                    $type,
                    (int)($d["nb_$key"] ?? 0),
                    0, // poids géré globalement
                ]);
            }
        }


    public function saveConsommations(int $resaId, array $d): void
    {
        $sql = "INSERT INTO consommations (reservation_id, boisson_id, quantite)
                VALUES (?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($this->getBoissons() as $b) {
            $key = 'boisson_' . $b['id'];
            $qty = (int)($d[$key] ?? 0);
            if ($qty > 0) {
                $stmt->execute([$resaId, $b['id'], $qty]);
            }
        }
    }

    public function getReservationDetails(int $idResa): ?array
    {
        $sql = "SELECT r.*, d.pays,
                CASE r.airport_choice WHEN 1 THEN d.nom_aeroport1 ELSE d.nom_aeroport2 END AS aeroport
                FROM reservations r
                JOIN destinations d ON d.id = r.destination_id
                WHERE r.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idResa]);
        $resa = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$resa) {
            return null;
        }

        // --- voyageurs ---
        $stmt = $this->pdo->prepare("
            SELECT type_age, quantite
            FROM voyageurs
            WHERE reservation_id = ?
        ");
        $stmt->execute([$idResa]);
        $resa['voyageurs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // initialisation
        $resa['nb_adultes'] = 0;
        $resa['nb_enfants'] = 0;
        $resa['nb_bebes']   = 0;

        foreach ($resa['voyageurs'] as $v) {
            $qty = (int) $v['quantite'];
            switch (strtolower($v['type_age'])) {
                case 'adult':
                case 'adulte':
                    $resa['nb_adultes'] += $qty;
                    break;

                case 'child':
                case 'enfant':
                    $resa['nb_enfants'] += $qty;
                    break;

                case 'baby':    
                case 'bebe':    
                case 'bébé':    
                    $resa['nb_bebes']   += $qty;
                    break;
            }
        }

        // --- bagages cabine stockés en base ---
        $resa['nb_cabin'] = isset($resa['nb_cabin']) ? (int)$resa['nb_cabin'] : 0;

        // --- consommations ---
        $stmt = $this->pdo->prepare("
            SELECT c.quantite, b.label, b.prix
            FROM consommations c
            JOIN boissons b ON b.id = c.boisson_id
            WHERE c.reservation_id = ?
        ");
        $stmt->execute([$idResa]);
        $resa['consommations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resa;
    }

        public function saveInvoice(int $reservationId, string $invoiceNumber, string $filePath,
                                    float $totalHt, float $tva, float $totalTtc): int
        {
            $sql = "INSERT INTO factures
                    (reservation_id, numero_facture, pdf_path, total_ht, tva, total_ttc)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$reservationId, $invoiceNumber, $filePath, $totalHt, $tva, $totalTtc]);
            return (int)$this->pdo->lastInsertId();
        }

        public function getRecapById(int $reservationId): array
        {
            $sql = "SELECT r.*, d.pays,
                    CASE r.airport_choice WHEN 1 THEN d.nom_aeroport1 ELSE d.nom_aeroport2 END AS aeroport
                    FROM reservations r
                    JOIN destinations d ON d.id = r.destination_id
                    WHERE r.id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$reservationId]);
            $resa = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            $stmt = $this->pdo->prepare("SELECT type_age AS item, quantite AS qty, NULL AS price
                                        FROM voyageurs
                                        WHERE reservation_id = ?");
            $stmt->execute([$reservationId]);
            $resa['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $this->pdo->prepare("SELECT b.label AS item, c.quantite AS qty, b.prix AS price
                                        FROM consommations c
                                        JOIN boissons b ON b.id = c.boisson_id
                                        WHERE c.reservation_id = ?");
            $stmt->execute([$reservationId]);
            $resa['items'] = array_merge($resa['items'], $stmt->fetchAll(PDO::FETCH_ASSOC));

            return $resa;
        }

    public function deleteItem(int $itemId, string $type): bool
    {
        if ($type === 'consommation') {
            $stmt = $this->pdo->prepare("DELETE FROM consommations WHERE id = ?");
            return $stmt->execute([$itemId]);
        }
        if ($type === 'voyageur') {
            $stmt = $this->pdo->prepare("DELETE FROM voyageurs WHERE id = ?");
            return $stmt->execute([$itemId]);
        }
        return false;
    }

    public function updateItem(int $itemId, string $type, int $qty): bool
    {
        if ($type === 'consommation') {
            $stmt = $this->pdo->prepare("UPDATE consommations SET quantite = ? WHERE id = ?");
            return $stmt->execute([$qty, $itemId]);
        }
        if ($type === 'voyageur') {
            $stmt = $this->pdo->prepare("UPDATE voyageurs SET quantite = ? WHERE id = ?");
            return $stmt->execute([$qty, $itemId]);
        }
        return false;
    }

    public function setStatus(int $reservationId, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $reservationId]);
    }

    public function getByContinent(): array
    {
        $sql = "SELECT continent, id, pays, nom_aeroport1 AS aeroport1, nom_aeroport2 AS aeroport2
                FROM destinations
                ORDER BY continent, pays";
        $stmt = $this->pdo->query($sql);
        $out = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $out[$r['continent']][$r['id']] = [
                'pays'      => $r['pays'],
                'aeroports' => [1=>$r['aeroport1'],2=>$r['aeroport2']],
            ];
        }
        return $out;
    }

    public function getTarifs(): array
    {
        $sql = "SELECT destination_id, tranche_age, prix_aero1, prix_aero2, prix_reduit_aero1, prix_reduit_aero2
                FROM tarifs";
        $stmt = $this->pdo->query($sql);
        $out = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $out[$r['destination_id']][$r['tranche_age']] = $r;
        }
        return $out;
    }

    public function getTarifsByDestinationAirport(int $destId, int $airportChoice): array
    {
        $sql = "SELECT tranche_age,
                    CASE WHEN :airport = 1 THEN prix_aero1 ELSE prix_aero2 END AS prix
                FROM tarifs
                WHERE destination_id = :dest";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dest' => $destId, 'airport' => $airportChoice]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // initialisation
        $out = [
        'adulte' => 0.0,
        'enfant' => 0.0,
        'bebe'   => 0.0,
        ];

        foreach ($rows as $r) {
            $ageLabel = strtolower(trim($r['tranche_age']));
            $price    = (float)$r['prix'];

            if (preg_match('#(12\+|adulte)#i', $ageLabel)) {
                $out['adulte'] = $price;
            }
            elseif (preg_match('#(2-11|enfant|child)#i', $ageLabel)) {
                $out['enfant'] = $price;
            }
            elseif (preg_match('#(<\s*2|bébé|baby)#i', $ageLabel)) {
                $out['bebe'] = $price;
            }
            else {
                // cas inattendu : on le logge pour debug
                error_log("Tarif inattendu pour tranche_age « {$r['tranche_age']} »");
            }
        }

        return $out;
    }

}
