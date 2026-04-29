<?php
// Controllers/UtilisateurController.php
session_start(); // Obligatoire bech n-khalliw l-user m-connecti
require_once '../Models/UtilisateurManager.php';

class UtilisateurController {
    
    // 1. Fonction el-Inscription (Register)
    public function sinscrire() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nomu = htmlspecialchars($_POST['nomu']);
            $email = htmlspecialchars($_POST['email']);
            $mdp = $_POST['mdp'];

            $um = new UtilisateurManager();
            if ($um->register($nomu, $email, $mdp)) {
                header("Location: ../views/connexion.php?msg=inscrit");
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }
    }

    // 2. Fonction el-Connexion (Login)
    public function seConnecter() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = htmlspecialchars($_POST['email']);
            $mdp = $_POST['mdp'];

            $um = new UtilisateurManager();
            $user = $um->login($email, $mdp);

            if ($user) {
                // Hna nest3amlu l-Sessions kima t-heb l-prof
                $_SESSION['user_id'] = $user['idu'];
                $_SESSION['user_nom'] = $user['nomu'];
                $_SESSION['user_role'] = $user['role']; // 'admin' walla 'client'

                header("Location: ../views/catalogue.php");
            } else {
                header("Location: ../views/connexion.php?err=1");
            }
        }
    }

    // 3. Fonction el-Déconnexion (Logout)
    public function seDeconnecter() {
        session_destroy();
        header("Location: ../views/connexion.php");
    }
}

// El-Routage mta el-Utilisateurs
$action = $_GET['action'] ?? '';
$controller = new UtilisateurController();

if ($action == 'inscription') {
    $controller->sinscrire();
} elseif ($action == 'login') {
    $controller->seConnecter();
} elseif ($action == 'logout') {
    $controller->seDeconnecter();
}