<?php
// Models/CommandeManager.php
require_once '../config/Database.php';

class CommandeManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Créer une nouvelle commande depuis le panier SESSION
    public function passerCommande(int $id_utilisateur, array $panier): int|false {
        // 1. Calculer le total
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        // 2. Insérer la commande principale
        $stmt = $this->db->prepare(
            "INSERT INTO commandes (id_utilisateur, total, statut) VALUES (?, ?, 'en_attente')"
        );
        $stmt->execute([$id_utilisateur, $total]);
        $id_commande = $this->db->lastInsertId();

        // 3. Insérer chaque produit de la commande
        $stmt2 = $this->db->prepare(
            "INSERT INTO commande_produits (id_commande, id_produit, quantite, prix_unitaire) 
             VALUES (?, ?, ?, ?)"
        );
        foreach ($panier as $idp => $item) {
            $stmt2->execute([$id_commande, $idp, $item['quantite'], $item['prix']]);

            // 4. Décrémenter le stock
            $this->db->prepare("UPDATE produits SET stock = stock - ? WHERE idp = ?")
                     ->execute([$item['quantite'], $idp]);
        }

        return $id_commande;
    }

    // Historique commandes d'un utilisateur
    public function getCommandesUtilisateur(int $id_utilisateur): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM commandes WHERE id_utilisateur = ? ORDER BY date_commande DESC"
        );
        $stmt->execute([$id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Détails d'une commande (produits)
    public function getDetailsCommande(int $id_commande): array {
        $stmt = $this->db->prepare(
            "SELECT cp.*, p.nomp, p.image 
             FROM commande_produits cp
             JOIN produits p ON cp.id_produit = p.idp
             WHERE cp.id_commande = ?"
        );
        $stmt->execute([$id_commande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Une commande par ID
    public function getCommande(int $id_commande): array|false {
        $stmt = $this->db->prepare("SELECT * FROM commandes WHERE idcm = ?");
        $stmt->execute([$id_commande]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Toutes les commandes (pour admin)
    public function getAllCommandes(): array {
        $stmt = $this->db->query(
            "SELECT c.*, u.nomu, u.email 
             FROM commandes c 
             JOIN utilisateurs u ON c.id_utilisateur = u.idu 
             ORDER BY c.date_commande DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
