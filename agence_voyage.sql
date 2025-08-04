-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 juin 2025 à 15:23
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `agence_voyage`
--

-- --------------------------------------------------------

--
-- Structure de la table `boissons`
--

CREATE TABLE `boissons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `boissons`
--

INSERT INTO `boissons` (`id`, `code`, `label`, `prix`) VALUES
(1, 'eau', '½ l Eau', '1.50'),
(2, 'biere', 'Cannette bière', '1.20'),
(3, 'vin', '¼ l Vin', '1.70'),
(4, 'coca', 'Cannette Coca', '1.80'),
(5, 'jus', 'Jus d’orange', '2.00');

-- --------------------------------------------------------

--
-- Structure de la table `consommations`
--

CREATE TABLE `consommations` (
  `id` int(10) UNSIGNED NOT NULL,
  `reservation_id` int(10) UNSIGNED NOT NULL,
  `boisson_id` int(10) UNSIGNED NOT NULL,
  `quantite` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `consommations`
--

INSERT INTO `consommations` (`id`, `reservation_id`, `boisson_id`, `quantite`) VALUES
(1, 4, 1, 1),
(2, 5, 1, 1),
(3, 6, 1, 1),
(4, 7, 1, 1),
(5, 8, 1, 1),
(6, 9, 1, 1),
(7, 10, 1, 1),
(8, 11, 1, 1),
(9, 12, 1, 1),
(10, 13, 1, 1),
(11, 14, 1, 1),
(12, 19, 1, 1),
(13, 20, 1, 1),
(14, 21, 1, 1),
(15, 24, 3, 1),
(16, 25, 1, 1),
(17, 26, 1, 2),
(18, 26, 4, 1),
(19, 26, 5, 2),
(20, 27, 1, 1),
(21, 30, 2, 1),
(22, 31, 1, 1),
(23, 32, 1, 2),
(24, 33, 1, 2),
(25, 34, 1, 2),
(26, 36, 1, 2),
(27, 37, 1, 2),
(28, 37, 5, 1),
(29, 38, 1, 4),
(30, 38, 5, 2),
(31, 39, 1, 3),
(32, 40, 1, 3),
(33, 41, 1, 3),
(34, 42, 1, 3),
(35, 43, 1, 3),
(36, 44, 1, 3),
(37, 45, 4, 1),
(38, 46, 1, 3),
(39, 47, 1, 3),
(40, 48, 1, 3),
(41, 49, 1, 3),
(42, 50, 1, 3),
(43, 51, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(10) UNSIGNED NOT NULL,
  `continent` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_aeroport1` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_aeroport2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `destinations`
--

INSERT INTO `destinations` (`id`, `continent`, `pays`, `nom_aeroport1`, `nom_aeroport2`) VALUES
(1, 'Afrique', 'Maroc', 'Marrakech', 'Agadir'),
(2, 'Afrique', 'Sénégal', 'Dakar', 'Touba'),
(3, 'Afrique', 'Kenya', 'Nairobi', 'Mombasa'),
(4, 'Europe', 'Espagne', 'Madrid', 'Barcelone'),
(5, 'Europe', 'Italie', 'Rome', 'Venise'),
(6, 'Europe', 'Portugal', 'Porto', 'Lisbonne'),
(7, 'Amériques', 'États-Unis', 'Los Angeles', 'New York'),
(8, 'Amériques', 'Brésil', 'Rio de Janeiro', 'Sao Paulo'),
(9, 'Amériques', 'Argentine', 'Buenos Aires', 'Mendoza'),
(10, 'Asie', 'Japon', 'Tokyo Narita', 'Osaka Kansai'),
(11, 'Asie', 'Chine', 'Pékin', 'Shanghai'),
(12, 'Asie', 'Turquie', 'Istanbul', 'Izmir');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` int(10) UNSIGNED NOT NULL,
  `reservation_id` int(10) UNSIGNED NOT NULL,
  `numero_facture` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_emission` datetime NOT NULL DEFAULT current_timestamp(),
  `total_ht` decimal(8,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL,
  `total_ttc` decimal(8,2) NOT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `reservation_id`, `numero_facture`, `date_emission`, `total_ht`, `tva`, `total_ttc`, `pdf_path`) VALUES
(3, 20, '20250514-0020', '2025-05-14 06:20:54', '420.00', '84.00', '504.00', 'invoices/20250514-0020.pdf'),
(4, 21, '20250514-0021', '2025-05-14 06:22:47', '410.00', '82.00', '492.00', 'invoices/20250514-0021.pdf'),
(6, 22, '20250514-0022', '2025-05-14 06:27:48', '910.00', '182.00', '1092.00', 'invoices/20250514-0022.pdf'),
(7, 24, '20250514-0024', '2025-05-14 14:33:30', '420.00', '84.00', '504.00', 'invoices/20250514-0024.pdf'),
(8, 25, '20250618-0025', '2025-06-18 22:53:34', '784.40', '156.88', '941.28', 'invoices/20250618-0025.pdf'),
(9, 26, '20250618-0026', '2025-06-18 23:16:47', '979.62', '195.92', '1175.54', 'invoices/20250618-0026.pdf'),
(10, 27, '20250622-0027', '2025-06-22 22:27:16', '600.00', '120.00', '720.00', 'invoices/20250622-0027.pdf'),
(11, 28, '20250622-0028', '2025-06-22 22:35:21', '420.00', '84.00', '504.00', 'invoices/20250622-0028.pdf'),
(12, 31, '20250623-0031', '2025-06-23 21:28:38', '545.37', '114.53', '659.90', 'invoices/20250623-0031.pdf'),
(14, 35, '20250623-0035', '2025-06-23 22:36:26', '441.00', '92.61', '533.61', 'invoices/20250623-0035.pdf'),
(15, 36, '20250623-0036', '2025-06-23 23:01:16', '483.14', '101.46', '584.60', 'invoices/20250623-0036.pdf'),
(16, 38, '20250624-0038', '2025-06-24 00:48:37', '1506.60', '316.39', '1822.99', 'invoices/20250624-0038.pdf'),
(17, 39, '20250624-0039', '2025-06-24 01:12:37', '1704.50', '357.95', '2062.45', 'invoices/20250624-0039.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `meals_choices`
--

CREATE TABLE `meals_choices` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('entree','plat','dessert') COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_unitaire` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `meals_choices`
--

INSERT INTO `meals_choices` (`id`, `type`, `label`, `prix_unitaire`) VALUES
(1, 'entree', 'Assiette de tomates', '0.00'),
(2, 'entree', 'Salade de fruits de mer', '0.00'),
(3, 'entree', 'Soupe à l’oignon', '0.00'),
(4, 'entree', 'Salade de légumes', '0.00'),
(5, 'plat', 'Viande hachée', '0.00'),
(6, 'plat', 'Plat du jour', '0.00'),
(7, 'plat', 'Poisson', '0.00'),
(8, 'plat', 'Poulet', '0.00'),
(9, 'dessert', 'Cake', '0.00'),
(10, 'dessert', 'Banane', '0.00'),
(11, 'dessert', 'Yaourt', '0.00'),
(12, 'dessert', 'Tarte aux pommes', '0.00');

-- --------------------------------------------------------

--
-- Structure de la table `modes_paiement`
--

CREATE TABLE `modes_paiement` (
  `id` int(10) UNSIGNED NOT NULL,
  `mode` enum('card','bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplement` decimal(6,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `modes_paiement`
--

INSERT INTO `modes_paiement` (`id`, `mode`, `supplement`) VALUES
(1, 'card', '30.00'),
(2, 'bank', '20.00'),
(3, '', '25.00');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_client` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gsm` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_id` int(10) UNSIGNED NOT NULL,
  `airport_choice` tinyint(3) UNSIGNED NOT NULL COMMENT '1 ou 2',
  `date_depart` date NOT NULL,
  `date_retour` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `entree_id` int(10) UNSIGNED DEFAULT NULL,
  `plat_id` int(10) UNSIGNED DEFAULT NULL,
  `dessert_id` int(10) UNSIGNED DEFAULT NULL,
  `paiement_mode` int(10) UNSIGNED NOT NULL,
  `poids_bagages` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_ht` decimal(8,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL,
  `total_ttc` decimal(8,2) NOT NULL,
  `status` enum('draft','final','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `bag_cabine_adult` tinyint(1) NOT NULL DEFAULT 0,
  `bag_cabine_enfant` tinyint(1) NOT NULL DEFAULT 0,
  `bag_cabine_bebe` tinyint(1) NOT NULL DEFAULT 0,
  `nb_cabin` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `nom`, `prenom`, `email_client`, `telephone`, `gsm`, `code_postal`, `adresse`, `destination_id`, `airport_choice`, `date_depart`, `date_retour`, `created_at`, `entree_id`, `plat_id`, `dessert_id`, `paiement_mode`, `poids_bagages`, `total_ht`, `tva`, `total_ttc`, `status`, `bag_cabine_adult`, `bag_cabine_enfant`, `bag_cabine_bebe`, `nb_cabin`) VALUES
(4, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:08:31', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(5, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:12:17', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(6, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:14:32', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(7, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:43:46', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(8, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:47:05', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(9, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:49:37', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(10, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:52:59', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(11, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:53:48', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(12, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 04:59:23', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(13, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 05:02:27', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(14, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-05-28', '2025-06-05', '2025-05-14 05:02:32', 0, 0, 0, 1, '0.00', '270.00', '54.00', '324.00', 'draft', 0, 0, 0, 0),
(15, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-29', '2025-06-06', '2025-05-14 05:17:08', 0, 0, 0, 2, '0.00', '910.00', '182.00', '1092.00', 'draft', 0, 0, 0, 0),
(16, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-29', '2025-06-06', '2025-05-14 05:17:09', 0, 0, 0, 2, '0.00', '910.00', '182.00', '1092.00', 'draft', 0, 0, 0, 0),
(17, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-29', '2025-06-06', '2025-05-14 05:17:10', 0, 0, 0, 2, '0.00', '910.00', '182.00', '1092.00', 'draft', 0, 0, 0, 0),
(18, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-29', '2025-06-06', '2025-05-14 05:17:10', 0, 0, 0, 2, '0.00', '910.00', '182.00', '1092.00', 'draft', 0, 0, 0, 0),
(19, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-16', '2025-06-08', '2025-05-14 06:10:56', 0, 0, 0, 1, '0.00', '420.00', '84.00', '504.00', 'draft', 0, 0, 0, 0),
(20, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-16', '2025-06-08', '2025-05-14 06:16:27', 0, 0, 0, 1, '0.00', '420.00', '84.00', '504.00', 'final', 0, 0, 0, 0),
(21, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-15', '2025-06-06', '2025-05-14 06:22:31', 0, 0, 0, 2, '0.00', '410.00', '82.00', '492.00', 'final', 0, 0, 0, 0),
(22, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-15', '2025-05-30', '2025-05-14 06:27:40', 0, 0, 0, 2, '0.00', '910.00', '182.00', '1092.00', 'final', 0, 0, 0, 0),
(23, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 10, 1, '2025-05-15', '2025-05-31', '2025-05-14 07:10:21', 0, 0, 0, 1, '0.00', '430.00', '86.00', '516.00', 'draft', 0, 0, 0, 0),
(24, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-05-15', '2025-06-08', '2025-05-14 14:30:50', 1, 8, 11, 1, '0.00', '420.00', '84.00', '504.00', 'final', 0, 0, 0, 0),
(25, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', NULL, '', 'Chaussée de Bruxelles\r\n12', 11, 1, '2025-06-19', '2025-06-23', '2025-06-18 22:53:17', 1, 0, 0, 2, '0.00', '784.40', '156.88', '941.28', 'final', 0, 0, 0, 0),
(26, 'Dupont', 'Clara', 'clara.dupont@example.com', '0499123456', NULL, '', '12 rue des Lilas, 1000 Bruxelles', 5, 1, '2025-09-15', '2025-10-10', '2025-06-18 23:13:38', 3, 6, 12, 1, '0.00', '979.62', '195.92', '1175.54', 'final', 0, 0, 0, 0),
(27, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0611223344', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-07-06', '2025-08-09', '2025-06-22 22:16:49', 1, 5, 9, 1, '34.00', '600.00', '120.00', '720.00', 'final', 0, 0, 0, 0),
(28, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0611223344', '7000', 'Chaussée de Bruxelles\r\n12', 5, 1, '2025-06-29', '2025-07-06', '2025-06-22 22:35:17', 0, 0, 0, 3, '20.00', '420.00', '84.00', '504.00', 'final', 0, 0, 0, 0),
(29, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0622334482', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-24', '2025-07-18', '2025-06-23 18:43:29', 1, 7, 11, 2, '20.00', '407.20', '85.51', '492.71', 'draft', 0, 0, 0, 0),
(30, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0611223344', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-24', '2025-07-06', '2025-06-23 19:24:36', 1, 7, 10, 2, '20.00', '407.88', '85.65', '493.53', 'draft', 0, 0, 0, 0),
(31, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0622334482', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-25', '2025-07-06', '2025-06-23 20:11:32', 1, 6, 10, 1, '25.00', '545.37', '114.53', '659.90', 'final', 0, 0, 0, 0),
(32, 'Zamble', 'Rodolphe Mizan', 'zamble@gmail.com', '0789458178', '0611223344', '7300', 'yopougon toit rouge', 8, 2, '2025-09-23', '2025-10-31', '2025-06-23 22:19:33', 1, 7, 9, 1, '20.00', '420.85', '88.38', '509.23', 'draft', 0, 0, 0, 0),
(33, 'Zamble', 'Rodolphe Mizan', 'zamble@gmail.com', '0789458178', '0611223344', '7300', 'yopougon toit rouge', 8, 2, '2025-09-23', '2025-10-31', '2025-06-23 22:21:12', 1, 7, 9, 1, '20.00', '430.35', '90.37', '520.72', 'draft', 0, 0, 0, 0),
(34, 'Zamble', 'Rodolphe Mizan', 'zamble@gmail.com', '0789458178', '0611223344', '7300', 'yopougon toit rouge', 8, 2, '2025-09-23', '2025-10-31', '2025-06-23 22:22:04', 1, 7, 9, 1, '20.00', '430.35', '90.37', '520.72', 'draft', 0, 0, 0, 0),
(35, 'Adou', 'Anny', 'anny@gmail.com', '0455133274', '0489123456', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-25', '2025-06-30', '2025-06-23 22:28:50', 1, 0, 0, 1, '20.00', '441.00', '92.61', '533.61', 'final', 0, 0, 0, 0),
(36, 'Adou', 'Ornella', 'adou@gmail.com', '0455133274', '0622334455', '7300', 'Rue du centenaire 6\r\n12', 10, 2, '2025-06-24', '2025-07-06', '2025-06-23 23:01:09', 1, 7, 11, 1, '20.00', '483.14', '101.46', '584.60', 'final', 0, 0, 0, 0),
(37, 'Doe', 'John', 'john.doe@example.com', '0123456789', '061234567', '1000', '1 Rue de l’Exemple', 9, 1, '2025-08-30', '2025-09-20', '2025-06-23 23:52:44', 1, 6, 10, 1, '100.00', '1464.75', '307.60', '1772.35', 'draft', 0, 0, 0, 0),
(38, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0611223344', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-09-30', '2025-10-10', '2025-06-24 00:47:24', 1, 6, 10, 1, '100.00', '1506.60', '316.39', '1822.99', 'final', 0, 0, 0, 0),
(39, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0622334455', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-30', '2025-07-30', '2025-06-24 01:12:22', 2, 7, 12, 3, '100.00', '1704.50', '357.95', '2062.45', 'final', 0, 0, 0, 0),
(40, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0622334455', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-30', '2025-07-30', '2025-06-24 01:19:13', 2, 7, 12, 3, '100.00', '1704.50', '357.95', '2062.45', 'draft', 0, 0, 0, 0),
(41, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0622334455', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-30', '2025-07-30', '2025-06-24 01:25:26', 2, 7, 12, 3, '100.00', '1704.50', '357.95', '2062.45', 'draft', 0, 0, 0, 0),
(42, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0622334455', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-30', '2025-07-30', '2025-06-24 01:39:50', 2, 7, 12, 3, '100.00', '1704.50', '357.95', '2062.45', 'draft', 0, 0, 0, 0),
(43, 'Zamble', 'Rodolphe Mizan', 'adouamalaetitiacarmelle@gmail.com', '0789458178', '0611223344', '7000', 'yopougon toit rouge', 9, 1, '2025-06-27', '2025-06-29', '2025-06-24 02:11:21', 1, 6, 9, 1, '100.00', '1680.21', '352.84', '2033.05', 'draft', 0, 0, 0, 0),
(44, 'Zamble', 'Rodolphe Mizan', 'adouamalaetitiacarmelle@gmail.com', '0789458178', '0611223344', '7000', 'yopougon toit rouge', 9, 1, '2025-06-27', '2025-06-29', '2025-06-24 02:14:39', 1, 6, 9, 1, '100.00', '1680.21', '352.84', '2033.05', 'draft', 0, 0, 0, 0),
(45, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0489123456', '7000', 'Chaussée de Bruxelles\r\n12', 5, 1, '2025-06-26', '2025-07-06', '2025-06-24 02:22:29', 0, 0, 0, 1, '20.00', '648.56', '136.20', '784.76', 'draft', 0, 0, 0, 0),
(46, 'Zamble', 'Rodolphe Mizan', 'zamble@gmail.com', '0789458178', '0489123456', '6000', 'yopougon toit rouge', 9, 1, '2025-06-26', '2025-07-06', '2025-06-24 09:41:12', 1, 6, 12, 1, '100.00', '1660.61', '348.73', '2009.34', 'draft', 0, 0, 0, 0),
(47, 'Zamble', 'Rodolphe Mizan', 'zamble@gmail.com', '0789458178', '0489123456', '6000', 'yopougon toit rouge', 9, 1, '2025-06-26', '2025-07-06', '2025-06-24 09:48:57', 1, 6, 12, 1, '100.00', '1660.61', '348.73', '2009.34', 'draft', 0, 0, 0, 0),
(48, 'Zamble', 'Rodolphe Mizan', 'zamble@gmail.com', '0789458178', '0489123456', '6000', 'yopougon toit rouge', 9, 1, '2025-06-26', '2025-07-06', '2025-06-24 10:10:44', 1, 6, 12, 1, '100.00', '1670.41', '350.79', '2021.20', 'draft', 0, 0, 0, 0),
(49, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0611223344', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-25', '2025-07-06', '2025-06-24 11:31:18', 1, 6, 11, 2, '100.00', '1675.31', '351.82', '2027.13', 'draft', 0, 0, 0, 0),
(50, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0611223344', '7000', 'Chaussée de Bruxelles\r\n12', 9, 1, '2025-06-25', '2025-07-06', '2025-06-24 11:53:15', 1, 6, 11, 2, '100.00', '1675.31', '351.82', '2027.13', 'draft', 0, 0, 0, 0),
(51, 'Adou', 'Ama', 'adouamalaetitiacarmelle@gmail.com', '0455133274', '0622334455', '7000', 'Chaussée de Bruxelles\r\n12', 3, 2, '2025-06-26', '2025-07-06', '2025-06-24 11:54:44', 1, 6, 11, 1, '100.00', '1748.81', '367.25', '2116.06', 'draft', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--

CREATE TABLE `tarifs` (
  `id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED NOT NULL,
  `tranche_age` enum('<2','2-11','12+') COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_aero1` decimal(8,2) NOT NULL,
  `prix_aero2` decimal(8,2) NOT NULL,
  `prix_reduit_aero1` decimal(8,2) NOT NULL,
  `prix_reduit_aero2` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tarifs`
--

INSERT INTO `tarifs` (`id`, `destination_id`, `tranche_age`, `prix_aero1`, `prix_aero2`, `prix_reduit_aero1`, `prix_reduit_aero2`) VALUES
(1, 1, '<2', '140.00', '150.00', '140.00', '150.00'),
(2, 1, '2-11', '180.00', '190.00', '180.00', '190.00'),
(3, 1, '12+', '250.00', '300.00', '250.00', '300.00'),
(4, 2, '<2', '200.00', '220.00', '200.00', '220.00'),
(5, 2, '2-11', '250.00', '290.00', '250.00', '290.00'),
(6, 2, '12+', '380.00', '400.00', '380.00', '400.00'),
(7, 3, '<2', '220.00', '250.00', '220.00', '250.00'),
(8, 3, '2-11', '290.00', '320.00', '290.00', '320.00'),
(9, 3, '12+', '400.00', '420.00', '400.00', '420.00'),
(10, 4, '<2', '150.00', '160.00', '150.00', '160.00'),
(11, 4, '2-11', '200.00', '220.00', '200.00', '220.00'),
(12, 4, '12+', '390.00', '400.00', '390.00', '400.00'),
(13, 5, '<2', '160.00', '190.00', '160.00', '190.00'),
(14, 5, '2-11', '220.00', '250.00', '220.00', '250.00'),
(15, 5, '12+', '400.00', '450.00', '400.00', '450.00'),
(16, 6, '<2', '150.00', '160.00', '150.00', '160.00'),
(17, 6, '2-11', '200.00', '230.00', '200.00', '230.00'),
(18, 6, '12+', '350.00', '380.00', '350.00', '380.00'),
(19, 7, '<2', '160.00', '170.00', '160.00', '170.00'),
(20, 7, '2-11', '180.00', '200.00', '180.00', '200.00'),
(21, 7, '12+', '280.00', '300.00', '280.00', '300.00'),
(22, 8, '<2', '180.00', '190.00', '180.00', '190.00'),
(23, 8, '2-11', '250.00', '260.00', '250.00', '260.00'),
(24, 8, '12+', '380.00', '400.00', '380.00', '400.00'),
(25, 9, '<2', '200.00', '220.00', '200.00', '220.00'),
(26, 9, '2-11', '260.00', '290.00', '260.00', '290.00'),
(27, 9, '12+', '390.00', '420.00', '390.00', '420.00'),
(28, 10, '<2', '200.00', '210.00', '200.00', '210.00'),
(29, 10, '2-11', '250.00', '260.00', '250.00', '260.00'),
(30, 10, '12+', '400.00', '450.00', '400.00', '450.00'),
(31, 11, '<2', '180.00', '190.00', '180.00', '190.00'),
(32, 11, '2-11', '240.00', '250.00', '240.00', '250.00'),
(33, 11, '12+', '390.00', '420.00', '390.00', '420.00'),
(34, 12, '<2', '100.00', '120.00', '100.00', '120.00'),
(35, 12, '2-11', '150.00', '160.00', '150.00', '160.00'),
(36, 12, '12+', '250.00', '260.00', '250.00', '260.00');

-- --------------------------------------------------------

--
-- Structure de la table `voyageurs`
--

CREATE TABLE `voyageurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `reservation_id` int(10) UNSIGNED NOT NULL,
  `type_age` enum('baby','child','adult') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int(10) UNSIGNED NOT NULL,
  `poids_kg` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `voyageurs`
--

INSERT INTO `voyageurs` (`id`, `reservation_id`, `type_age`, `quantite`, `poids_kg`) VALUES
(1, 4, 'baby', 0, '0.00'),
(2, 4, 'child', 0, '0.00'),
(3, 4, 'adult', 0, '0.00'),
(4, 5, 'baby', 0, '0.00'),
(5, 5, 'child', 0, '0.00'),
(6, 5, 'adult', 0, '0.00'),
(7, 6, 'baby', 0, '0.00'),
(8, 6, 'child', 0, '0.00'),
(9, 6, 'adult', 0, '0.00'),
(10, 7, 'baby', 0, '0.00'),
(11, 7, 'child', 0, '0.00'),
(12, 7, 'adult', 0, '0.00'),
(13, 8, 'baby', 0, '0.00'),
(14, 8, 'child', 0, '0.00'),
(15, 8, 'adult', 0, '0.00'),
(16, 9, 'baby', 0, '0.00'),
(17, 9, 'child', 0, '0.00'),
(18, 9, 'adult', 0, '0.00'),
(19, 10, 'baby', 0, '0.00'),
(20, 10, 'child', 0, '0.00'),
(21, 10, 'adult', 0, '0.00'),
(22, 11, 'baby', 0, '0.00'),
(23, 11, 'child', 0, '0.00'),
(24, 11, 'adult', 0, '0.00'),
(25, 12, 'baby', 0, '0.00'),
(26, 12, 'child', 0, '0.00'),
(27, 12, 'adult', 0, '0.00'),
(28, 13, 'baby', 0, '0.00'),
(29, 13, 'child', 0, '0.00'),
(30, 13, 'adult', 0, '0.00'),
(31, 14, 'baby', 0, '0.00'),
(32, 14, 'child', 0, '0.00'),
(33, 14, 'adult', 0, '0.00'),
(34, 15, 'baby', 0, '0.00'),
(35, 15, 'child', 0, '0.00'),
(36, 15, 'adult', 0, '0.00'),
(37, 16, 'baby', 0, '0.00'),
(38, 16, 'child', 0, '0.00'),
(39, 16, 'adult', 0, '0.00'),
(40, 17, 'baby', 0, '0.00'),
(41, 17, 'child', 0, '0.00'),
(42, 17, 'adult', 0, '0.00'),
(43, 18, 'baby', 0, '0.00'),
(44, 18, 'child', 0, '0.00'),
(45, 18, 'adult', 0, '0.00'),
(46, 19, 'baby', 0, '0.00'),
(47, 19, 'child', 0, '0.00'),
(48, 19, 'adult', 0, '0.00'),
(49, 20, 'baby', 0, '0.00'),
(50, 20, 'child', 0, '0.00'),
(51, 20, 'adult', 0, '0.00'),
(52, 21, 'baby', 0, '0.00'),
(53, 21, 'child', 0, '0.00'),
(54, 21, 'adult', 0, '0.00'),
(55, 22, 'baby', 0, '0.00'),
(56, 22, 'child', 0, '0.00'),
(57, 22, 'adult', 0, '0.00'),
(58, 23, 'baby', 0, '0.00'),
(59, 23, 'child', 0, '0.00'),
(60, 23, 'adult', 0, '0.00'),
(61, 24, 'baby', 0, '0.00'),
(62, 24, 'child', 0, '0.00'),
(63, 24, 'adult', 0, '0.00'),
(64, 25, 'baby', 0, '0.00'),
(65, 25, 'child', 0, '0.00'),
(66, 25, 'adult', 0, '0.00'),
(67, 26, 'baby', 0, '0.00'),
(68, 26, 'child', 0, '0.00'),
(69, 26, 'adult', 0, '0.00'),
(70, 27, '', 0, '0.00'),
(71, 27, 'child', 0, '0.00'),
(72, 27, 'adult', 1, '0.00'),
(73, 28, '', 0, '0.00'),
(74, 28, 'child', 0, '0.00'),
(75, 28, 'adult', 1, '0.00'),
(76, 29, '', 0, '0.00'),
(77, 29, 'child', 0, '0.00'),
(78, 29, 'adult', 1, '0.00'),
(79, 30, '', 0, '0.00'),
(80, 30, 'child', 0, '0.00'),
(81, 30, 'adult', 1, '0.00'),
(82, 31, '', 0, '0.00'),
(83, 31, 'child', 0, '0.00'),
(84, 31, 'adult', 1, '0.00'),
(85, 32, '', 0, '0.00'),
(86, 32, 'child', 1, '0.00'),
(87, 32, 'adult', 1, '0.00'),
(88, 33, '', 0, '0.00'),
(89, 33, 'child', 1, '0.00'),
(90, 33, 'adult', 1, '0.00'),
(91, 34, '', 0, '0.00'),
(92, 34, 'child', 1, '0.00'),
(93, 34, 'adult', 1, '0.00'),
(94, 35, '', 0, '0.00'),
(95, 35, 'child', 2, '0.00'),
(96, 35, 'adult', 1, '0.00'),
(97, 36, '', 0, '0.00'),
(98, 36, 'child', 0, '0.00'),
(99, 36, 'adult', 1, '0.00'),
(100, 37, '', 1, '0.00'),
(101, 37, 'child', 1, '0.00'),
(102, 37, 'adult', 2, '0.00'),
(103, 38, '', 1, '0.00'),
(104, 38, 'child', 1, '0.00'),
(105, 38, 'adult', 2, '0.00'),
(106, 39, '', 1, '0.00'),
(107, 39, 'child', 1, '0.00'),
(108, 39, 'adult', 1, '0.00'),
(109, 40, '', 1, '0.00'),
(110, 40, 'child', 1, '0.00'),
(111, 40, 'adult', 1, '0.00'),
(112, 41, '', 1, '0.00'),
(113, 41, 'child', 1, '0.00'),
(114, 41, 'adult', 1, '0.00'),
(115, 42, '', 1, '0.00'),
(116, 42, 'child', 1, '0.00'),
(117, 42, 'adult', 1, '0.00'),
(118, 43, '', 1, '0.00'),
(119, 43, 'child', 1, '0.00'),
(120, 43, 'adult', 1, '0.00'),
(121, 44, '', 1, '0.00'),
(122, 44, 'child', 1, '0.00'),
(123, 44, 'adult', 1, '0.00'),
(124, 45, '', 1, '0.00'),
(125, 45, 'child', 0, '0.00'),
(126, 45, 'adult', 1, '0.00'),
(127, 46, '', 1, '0.00'),
(128, 46, 'child', 1, '0.00'),
(129, 46, 'adult', 1, '0.00'),
(130, 47, '', 1, '0.00'),
(131, 47, 'child', 1, '0.00'),
(132, 47, 'adult', 1, '0.00'),
(133, 48, '', 1, '0.00'),
(134, 48, 'child', 1, '0.00'),
(135, 48, 'adult', 1, '0.00'),
(136, 49, '', 1, '0.00'),
(137, 49, 'child', 1, '0.00'),
(138, 49, 'adult', 1, '0.00'),
(139, 50, '', 1, '0.00'),
(140, 50, 'child', 1, '0.00'),
(141, 50, 'adult', 1, '0.00'),
(142, 51, '', 1, '0.00'),
(143, 51, 'child', 1, '0.00'),
(144, 51, 'adult', 1, '0.00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `boissons`
--
ALTER TABLE `boissons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `consommations`
--
ALTER TABLE `consommations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_consos_resa` (`reservation_id`),
  ADD KEY `idx_consos_boisson` (`boisson_id`);

--
-- Index pour la table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_destinations_pays_aeroports` (`pays`,`nom_aeroport1`,`nom_aeroport2`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_facture` (`numero_facture`),
  ADD KEY `idx_factures_resa` (`reservation_id`);

--
-- Index pour la table `meals_choices`
--
ALTER TABLE `meals_choices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modes_paiement`
--
ALTER TABLE `modes_paiement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reservations_destination` (`destination_id`),
  ADD KEY `idx_reservations_paiement` (`paiement_mode`);

--
-- Index pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tarifs_destination` (`destination_id`);

--
-- Index pour la table `voyageurs`
--
ALTER TABLE `voyageurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_voyageurs_resa` (`reservation_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `boissons`
--
ALTER TABLE `boissons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `consommations`
--
ALTER TABLE `consommations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `meals_choices`
--
ALTER TABLE `meals_choices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `modes_paiement`
--
ALTER TABLE `modes_paiement`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `tarifs`
--
ALTER TABLE `tarifs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `voyageurs`
--
ALTER TABLE `voyageurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consommations`
--
ALTER TABLE `consommations`
  ADD CONSTRAINT `consommations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consommations_ibfk_2` FOREIGN KEY (`boisson_id`) REFERENCES `boissons` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`paiement_mode`) REFERENCES `modes_paiement` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD CONSTRAINT `tarifs_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `voyageurs`
--
ALTER TABLE `voyageurs`
  ADD CONSTRAINT `voyageurs_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
