-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 19 avr. 2026 à 11:11
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd_eshop`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `idc` int(11) NOT NULL,
  `nomc` varchar(100) NOT NULL,
  `descriptionc` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`idc`, `nomc`, `descriptionc`, `image`) VALUES
(1, 'Maquillage', 'Produits pour le teint, les yeux et les lèvres', NULL),
(2, 'Soins Visage', 'Crèmes, sérums et nettoyants', NULL),
(3, 'Parfums', 'Eaux de parfum et brumes corporelles', NULL),
(4, 'Soins Cheveux', 'Shampoings et masques capillaires', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `idcm` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` enum('en_attente','paye','expedie','livre','annule') DEFAULT 'en_attente',
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande_produits`
--

CREATE TABLE `commande_produits` (
  `idcmp` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `idp` int(11) NOT NULL,
  `nomp` varchar(150) NOT NULL,
  `descriptionp` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`idp`, `nomp`, `descriptionp`, `prix`, `stock`, `image`, `id_categorie`) VALUES
(1, 'Fond de Teint Lumineux', 'Couvrance moyenne pour un fini naturel', 35.00, 10, 'fond.jpg', 1),
(2, 'Rouge à Lèvres Velours', 'Tenue 12h fini mat', 22.50, 15, 'rouge.jpg', 1),
(3, 'Sérum Hydratant', 'À base d acide hyaluronique', 48.00, 8, 'serum.jpg', 2),
(4, 'Crème de Nuit', 'Régénérante et apaisante', 42.00, 5, 'creme.jpg', 2),
(5, 'Parfum Mystère', 'Notes de vanille et d ambre', 110.00, 3, 'parfum.jpg', 3),
(6, 'Shampoing Brillance', 'Pour cheveux secs et ternes', 18.00, 20, 'shampoing.jpg', 4),
(7, 'yasmin', NULL, 50.00, 22, 'fond.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `idu` int(11) NOT NULL,
  `nomu` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','client') DEFAULT 'client',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`idc`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`idcm`),
  ADD KEY `fk_comm_util` (`id_utilisateur`);

--
-- Index pour la table `commande_produits`
--
ALTER TABLE `commande_produits`
  ADD PRIMARY KEY (`idcmp`),
  ADD KEY `fk_piv_comm` (`id_commande`),
  ADD KEY `fk_piv_prod` (`id_produit`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`idp`),
  ADD KEY `fk_prod_cat` (`id_categorie`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`idu`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `idc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `idcm` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande_produits`
--
ALTER TABLE `commande_produits`
  MODIFY `idcmp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `idu` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `fk_comm_util` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`idu`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande_produits`
--
ALTER TABLE `commande_produits`
  ADD CONSTRAINT `fk_piv_comm` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`idcm`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_piv_prod` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`idp`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_prod_cat` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`idc`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
