<?php
// Models/Uploader.php

class Uploader {
    private string $targetDirectory;
    private array $allowedExtensions;
    private int $maxSize;
    private array $errors = [];

    public function __construct(
        string $targetDirectory = '../public/images/',
        array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        int $maxSize = 2097152 // 2MB
    ) {
        $this->targetDirectory = rtrim($targetDirectory, '/') . '/';
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
        $this->maxSize = $maxSize;
    }

    public function upload(array $fileData): string|false {
        $this->errors = [];

        // 1. Vérifier le code d'erreur PHP
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            $this->addErrorByCode($fileData['error']);
            return false;
        }

        // 2. Vérifier l'extension
        $extension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            $this->errors[] = "Extension non autorisée : " . $extension;
            return false;
        }

        // 3. Vérifier la taille
        if ($fileData['size'] > $this->maxSize) {
            $this->errors[] = "Fichier trop lourd. Max : " . ($this->maxSize / 1024 / 1024) . " Mo.";
            return false;
        }

        // 4. Vérifier que le dossier destination existe
        if (!is_dir($this->targetDirectory)) {
            $this->errors[] = "Dossier de destination introuvable : " . $this->targetDirectory;
            return false;
        }

        // 5. Générer un nom unique pour éviter les collisions
        $newFileName = uniqid('img_', true) . '.' . $extension;
        $destination = $this->targetDirectory . $newFileName;

        // 6. Déplacer le fichier
        if (move_uploaded_file($fileData['tmp_name'], $destination)) {
            return $newFileName;
        }

        $this->errors[] = "Erreur lors du déplacement du fichier.";
        return false;
    }

    // Retourne les erreurs pour les afficher dans la View
    public function getErrors(): array {
        return $this->errors;
    }

    // Traduit les codes PHP en messages lisibles
    private function addErrorByCode(int $errorCode): void {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->errors[] = "Le fichier dépasse la taille limite autorisée.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->errors[] = "Le fichier n'a été que partiellement téléchargé.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->errors[] = "Aucun fichier sélectionné.";
                break;
            default:
                $this->errors[] = "Erreur inconnue lors de l'upload.";
                break;
        }
    }
}