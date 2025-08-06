# CarmoFly

Un site de réservation de voyages développé en PHP dans le cadre d’un projet scolaire. CarmoFly permet à un utilisateur de réserver des allers-retours vers un ensemble de destinations prédéfinies, de sélectionner son menu et ses boissons, d’ajouter des bagages, et de générer un récapitulatif détaillé avec prix TTC.

## Table des matières

- [Fonctionnalités](#fonctionnalités)  
- [Technologies](#technologies)  
- [Installation](#installation)  
- [Usage](#usage)  
- [Configuration des tarifs et règles](#configuration-des-tarifs-et-règles)  
- [Contribuer](#contribuer)  
- [Licence](#licence)  
- [Auteur](#auteur)  

## Fonctionnalités

- Liste de destinations (document A) au départ de Bruxelles Charleroi Sud  
- Calcul automatique du tarif selon :  
  - Tranche d’âge (bébés, enfants, adultes)  
  - Réservation à moins de 3 mois (pas de réduction) ou à ≥ 3 mois (– 10 %)  
  - Supplément bagages (au-delà de 25 kg à 20 €/kg)  
  - Choix d’un menu (entrée, plat, dessert) et de boissons (document C)  
  - Mode de paiement :  
    - Carte bancaire internationale (+ 30 €)  
    - Virement bancaire (+ 20 €)  
- Application de la TVA à 20 % sur tous les tarifs HT  
- Validation des saisies (formats de date, email, téléphone, longueurs de champs…)  
- Génération d’un récapitulatif PDF/HTML à envoyer au responsable voyage (document D)  

## Technologies

- **Back-end** : PHP  
- **Front-end** : HTML5, CSS3, JavaScript  
- **Serveur** : Apache (XAMPP, WAMP ou PHP intégré)  
- **Base de données** : (optionnel) aucun si tout est géré en sessions/fichiers plats

## Installation

1. Cloner le dépôt  
   ```bash
   git clone https://github.com/Ama28-hub/CarmoFly.git
   cd CarmoFly
   # CarmoFly

