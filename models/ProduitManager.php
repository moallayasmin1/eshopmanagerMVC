<?php
// Models/ProduitManager.php
require_once '../config/Database.php';
require_once 'Product.php';

class ProduitManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ========== READ ==========

    // Tous les produits avec leur catégorie
    public function getAll(): array {
        $sql = "SELECT p.*, c.nomc FROM produits p 
                LEFT JOIN categories c ON p.id_categorie = c.idc";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Un seul produit par id
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT p.*, c.nomc FROM produits p 
                LEFT JOIN categories c ON p.id_categorie = c.idc 
                WHERE p.idp = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Produits par catégorie
    public function findByCategory(int $id_cat): array {
        $stmt = $this->db->prepare("SELECT p.*, c.nomc FROM produits p 
                LEFT JOIN categories c ON p.id_categorie = c.idc 
                WHERE p.id_categorie = ?");
        $stmt->execute([$id_cat]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recherche par nom
    public function findByName(string $name): array {
        $stmt = $this->db->prepare("SELECT p.*, c.nomc FROM produits p 
                LEFT JOIN categories c ON p.id_categorie = c.idc 
                WHERE p.nomp LIKE :name");
        $stmt->execute(['name' => '%' . $name . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recherche multicritères (nom ET/OU catégorie)
    public function search(string $keyword = '', int $id_cat = 0): array {
        $sql = "SELECT p.*, c.nomc FROM produits p 
                LEFT JOIN categories c ON p.id_categorie = c.idc 
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (p.nomp LIKE :k OR c.nomc LIKE :k)";
            $params['k'] = '%' . $keyword . '%';
        }

        if ($id_cat > 0) {
            $sql .= " AND p.id_categorie = :cat";
            $params['cat'] = $id_cat;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Toutes les catégories (pour les menus déroulants)
    public function getAllCategories(): array {
        return $this->db->query("SELECT * FROM categories ORDER BY nomc")->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========== CREATE ==========

    // Vérifier si produit existe avant insertion
    public function exists(string $nomp): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM produits WHERE nomp = ?");
        $stmt->execute([$nomp]);
        return $stmt->fetchColumn() > 0;
    }

    // Ajouter un produit
    public function add(string $nom, float $prix, int $stock, int $id_cat, string $image): bool {
        $req = $this->db->prepare("INSERT INTO produits (nomp, prix, stock, id_categorie, image) VALUES (?, ?, ?, ?, ?)");
        return $req->execute([$nom, $prix, $stock, $id_cat, $image]);
    }

    // ========== UPDATE ==========

    public function update(int $id, string $nom, float $prix, int $stock, int $id_cat): bool {
        $sql = "UPDATE produits SET nomp = :nom, prix = :prix, stock = :stock, id_categorie = :id_cat 
                WHERE idp = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom',    $nom);
        $stmt->bindValue(':prix',   $prix);
        $stmt->bindValue(':stock',  $stock);
        $stmt->bindValue(':id_cat', $id_cat);
        $stmt->bindValue(':id',     $id);
        return $stmt->execute();
    }

    // ========== DELETE ==========

    public function delete(int $id): bool {
        $req = $this->db->prepare("DELETE FROM produits WHERE idp = ?");
        return $req->execute([$id]);
    }
}
