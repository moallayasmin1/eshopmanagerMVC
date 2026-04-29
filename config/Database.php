<?php
// Models/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    // Constructeur privé : empêche de créer l'objet avec 'new Database()'
    private function __construct() {
        $host = 'localhost';
        $dbname = 'bd_eshop';
        $user = 'root';
        $password = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
            // Activation des erreurs PDO pour le débogage
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // La méthode statique pour récupérer la connexion partout dans le projet
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}