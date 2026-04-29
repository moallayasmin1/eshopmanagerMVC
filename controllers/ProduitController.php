<?php
// Controllers/ProduitController.php
require_once '../Models/ProduitManager.php';
require_once '../Models/Uploader.php';

class ProduitController {

    // ========== AJOUTER ==========
    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $nom    = htmlspecialchars(trim($_POST['nom']    ?? ''), ENT_QUOTES);
        $prix   = $_POST['prix']   ?? '';
        $stock  = $_POST['stock']  ?? '';
        $id_cat = $_POST['id_categorie'] ?? 0;

        // Validation
        if (empty($nom) || !is_numeric($prix) || !is_numeric($stock)) {
            header("Location: ../views/ajouter_produit.php?err=champs");
            exit;
        }
        if ((float)$prix < 0 || (int)$stock < 0) {
            header("Location: ../views/ajouter_produit.php?err=positif");
            exit;
        }

        // Vérifier si le produit existe déjà (demande du prof)
        $manager = new ProduitManager();
        if ($manager->exists($nom)) {
            header("Location: ../views/ajouter_produit.php?err=existe");
            exit;
        }

        // Upload image
        $uploader = new Uploader();
        $nomImage = $uploader->upload($_FILES['image_file'] ?? []);
        if (!$nomImage) {
            $errMsg = urlencode(implode(', ', $uploader->getErrors()));
            header("Location: ../views/ajouter_produit.php?err=image&msg=$errMsg");
            exit;
        }

        // Insertion
        $res = $manager->add($nom, (float)$prix, (int)$stock, (int)$id_cat, $nomImage);
        if ($res) {
            header("Location: ../views/catalogue.php?success=1");
        } else {
            header("Location: ../views/ajouter_produit.php?err=db");
        }
        exit;
    }

    // ========== MODIFIER ==========
    public function modifier() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id     = (int)($_POST['id']    ?? 0);
        $nom    = htmlspecialchars(trim($_POST['nom']  ?? ''), ENT_QUOTES);
        $prix   = $_POST['prix']  ?? '';
        $stock  = $_POST['stock'] ?? '';
        $id_cat = (int)($_POST['id_categorie'] ?? 0);

        if (empty($nom) || !is_numeric($prix) || !is_numeric($stock)) {
            header("Location: ../views/modifier_produit.php?id=$id&err=champs");
            exit;
        }

        $manager = new ProduitManager();
        $res = $manager->update($id, $nom, (float)$prix, (int)$stock, $id_cat);

        if ($res) {
            header("Location: ../views/catalogue.php?success=2");
        } else {
            header("Location: ../views/modifier_produit.php?id=$id&err=db");
        }
        exit;
    }

    // ========== SUPPRIMER ==========
    public function supprimer(int $id) {
        $pm = new ProduitManager();
        if ($pm->delete($id)) {
            header("Location: ../views/catalogue.php?msg=deleted");
        } else {
            header("Location: ../views/catalogue.php?err=delete");
        }
        exit;
    }

    // ========== RECHERCHE ==========
    public function rechercher() {
        $keyword = $_GET['q']   ?? '';
        $id_cat  = (int)($_GET['cat'] ?? 0);

        $manager  = new ProduitManager();
        $products = $manager->search($keyword, $id_cat);
        $categories = $manager->getAllCategories();

        include '../views/rechercher.php';
    }
}

// ========== ROUTAGE ==========
$action = $_GET['action'] ?? '';
$controller = new ProduitController();

if ($action == 'ajouter') {
    $controller->ajouter();
} elseif ($action == 'modifier') {
    $controller->modifier();
} elseif ($action == 'supprimer') {
    $controller->supprimer((int)($_GET['id'] ?? 0));
} elseif ($action == 'rechercher') {
    $controller->rechercher();
}
