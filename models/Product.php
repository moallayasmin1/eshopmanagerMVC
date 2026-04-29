<?php
// Models/Product.php

class Product {

    public function __construct(
        private int    $idp        = 0,
        private string $nomp       = '',
        private float  $prix       = 0.0,
        private int    $stock      = 0,
        private string $image      = 'produit.jpg',
        private int    $id_categorie = 0,
        private string $nomc       = '',
        private ?string $descriptionp = null
    ) {}

    // ===== GETTERS =====
    public function getIdp(): int           { return $this->idp; }
    public function getNomp(): string        { return $this->nomp; }
    public function getPrix(): float         { return $this->prix; }
    public function getStock(): int          { return $this->stock; }
    public function getImage(): string       { return $this->image; }
    public function getIdCategorie(): int    { return $this->id_categorie; }
    public function getNomc(): string        { return $this->nomc; }
    public function getDescriptionp(): ?string { return $this->descriptionp; }

    // ===== SETTERS avec validation =====
    public function setNomp(string $nomp): void {
        $this->nomp = $nomp;
    }

    public function setPrix(float $prix): void {
        if ($prix < 0) {
            echo "Erreur : Le prix ne peut pas être négatif.<br>";
            return;
        }
        $this->prix = $prix;
    }

    public function setStock(int $stock): void {
        if ($stock < 0) {
            echo "Erreur : Le stock ne peut pas être négatif.<br>";
            return;
        }
        $this->stock = $stock;
    }

    public function setImage(string $image): void   { $this->image = $image; }
    public function setIdCategorie(int $id): void   { $this->id_categorie = $id; }
    public function setDescriptionp(?string $d): void { $this->descriptionp = $d; }

    // ===== METHODES MAGIQUES =====
    public function __toString(): string {
        return "Produit [" . $this->idp . "] " . $this->nomp .
               " | Prix: " . number_format($this->prix, 3) . " TND" .
               " | Stock: " . $this->stock .
               " | Catégorie: " . $this->nomc;
    }

    public function __get(string $name) {
        return property_exists($this, $name) ? $this->$name : "Erreur : attribut inexistant";
    }

    public function __set(string $name, $value): void {
        if (property_exists($this, $name)) {
            if ($name === 'prix' && $value < 0) {
                echo "Erreur : Le prix ne peut pas être négatif.<br>";
                return;
            }
            if ($name === 'stock' && $value < 0) {
                echo "Erreur : Le stock ne peut pas être négatif.<br>";
                return;
            }
            $this->$name = $value;
        }
    }

    public function __isset(string $name): bool {
        return isset($this->$name);
    }
}
