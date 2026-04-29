<?php
// Models/UtilisateurManager.php
require_once '../config/Database.php';

class UtilisateurManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Inscription d'un nouvel utilisateur
    public function register($nomu, $email, $mdp) {
        // Hachage du mot de passe pour la sécurité
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
        $req = $this->db->prepare("INSERT INTO utilisateurs (nomu, email, mot_de_passe, role) VALUES (?, ?, ?, 'client')");
        return $req->execute([$nomu, $email, $mdp_hache]);
    }

    // Connexion : Vérifier l'email et le mot de passe
    public function login($email, $mdp) {
        $req = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $req->execute([$email]);
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            return $user; // Retourne les infos de l'utilisateur pour la SESSION
        }
        return false;
    }
}