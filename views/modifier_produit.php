<?php
// views/modifier_produit.php
session_start();
require_once '../Models/ProduitManager.php';

$id = (int)($_GET['id'] ?? 0);
$manager = new ProduitManager();
$produit = $manager->getById($id);
$categories = $manager->getAllCategories();

if (!$produit) {
    header("Location: catalogue.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Modifier Produit - Admin</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div style="max-width:500px; margin:30px auto; padding:30px; border:1px solid #eee; border-radius:10px;">
        <h1>Modifier le produit</h1>

        <?php if (isset($_GET['err'])): ?>
            <p style="color:red;">Veuillez vérifier les champs.</p>
        <?php endif; ?>

        <form action="../Controllers/ProduitController.php?action=modifier" method="POST">
            <input type="hidden" name="id" value="<?= $produit['idp'] ?>">

            <label>Nom du cosmétique :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($produit['nomp']) ?>" required>

            <label>Prix (DT) :</label>
            <input type="number" step="0.001" name="prix" value="<?= $produit['prix'] ?>" required>

            <label>Stock :</label>
            <input type="number" name="stock" value="<?= $produit['stock'] ?>" required>

            <label>Catégorie :</label>
            <select name="id_categorie">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['idc'] ?>" <?= $cat['idc'] == $produit['id_categorie'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nomc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>
            <button type="submit">💾 Enregistrer les modifications</button>
            <a href="catalogue.php" style="margin-left:15px; color:#D4607A;">Annuler</a>
        </form>
    </div>
</body>
</html>
