<?php
// Controllers/PanierController.php
session_start();
require_once '../Models/CommandeManager.php';
require_once '../Models/ProduitManager.php';

class PanierController {

    // ========== AJOUTER AU PANIER ==========
    public function ajouter() {
        $idp      = (int)($_GET['id'] ?? 0);
        $quantite = (int)($_POST['quantite'] ?? 1);

        if ($idp <= 0) {
            header("Location: ../views/catalogue.php?err=produit");
            exit;
        }

        // Vérifier que le produit existe et a du stock
        $manager = new ProduitManager();
        $produit = $manager->getById($idp);

        if (!$produit || $produit['stock'] <= 0) {
            header("Location: ../views/catalogue.php?err=stock");
            exit;
        }

        // Initialiser le panier si besoin
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        // Si produit déjà dans panier → augmenter quantité
        if (isset($_SESSION['panier'][$idp])) {
            $nouvelleQte = $_SESSION['panier'][$idp]['quantite'] + $quantite;
            // Ne pas dépasser le stock
            if ($nouvelleQte > $produit['stock']) {
                $nouvelleQte = $produit['stock'];
            }
            $_SESSION['panier'][$idp]['quantite'] = $nouvelleQte;
        } else {
            // Nouveau produit dans le panier
            $_SESSION['panier'][$idp] = [
                'idp'      => $idp,
                'nomp'     => $produit['nomp'],
                'prix'     => (float)$produit['prix'],
                'image'    => $produit['image'],
                'nomc'     => $produit['nomc'] ?? '',
                'quantite' => min($quantite, $produit['stock'])
            ];
        }

        header("Location: ../views/catalogue.php?added=1");
        exit;
    }

    // ========== MODIFIER QUANTITÉ ==========
    public function modifier() {
        $idp      = (int)($_POST['idp'] ?? 0);
        $quantite = (int)($_POST['quantite'] ?? 1);

        if ($idp > 0 && isset($_SESSION['panier'][$idp])) {
            if ($quantite <= 0) {
                unset($_SESSION['panier'][$idp]);
            } else {
                $_SESSION['panier'][$idp]['quantite'] = $quantite;
            }
        }

        header("Location: ../views/panier.php");
        exit;
    }

    // ========== SUPPRIMER DU PANIER ==========
    public function supprimer() {
        $idp = (int)($_GET['id'] ?? 0);
        if (isset($_SESSION['panier'][$idp])) {
            unset($_SESSION['panier'][$idp]);
        }
        header("Location: ../views/panier.php");
        exit;
    }

    // ========== VIDER LE PANIER ==========
    public function vider() {
        $_SESSION['panier'] = [];
        header("Location: ../views/panier.php");
        exit;
    }

    // ========== PASSER COMMANDE ==========
    public function passerCommande() {
        // Vérifier connexion
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../views/connexion.php?msg=login_required");
            exit;
        }

        // Vérifier panier non vide
        if (empty($_SESSION['panier'])) {
            header("Location: ../views/panier.php?err=vide");
            exit;
        }

        $cm = new CommandeManager();
        $id_commande = $cm->passerCommande($_SESSION['user_id'], $_SESSION['panier']);

        if ($id_commande) {
            // Vider le panier après commande
            $_SESSION['panier'] = [];
            header("Location: ../views/commande_succes.php?id=$id_commande");
        } else {
            header("Location: ../views/panier.php?err=commande");
        }
        exit;
    }
}

// ========== ROUTAGE ==========
$action = $_GET['action'] ?? '';
$controller = new PanierController();

if ($action == 'ajouter')          $controller->ajouter();
elseif ($action == 'modifier')     $controller->modifier();
elseif ($action == 'supprimer')    $controller->supprimer();
elseif ($action == 'vider')        $controller->vider();
elseif ($action == 'commander')    $controller->passerCommande();
