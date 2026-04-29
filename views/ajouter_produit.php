<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Ajouter un produit - Admin</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div style="max-width: 500px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h1>Ajouter un nouveau produit</h1>

        <?php if (isset($_GET['success'])): ?>
            <p style="color:green; text-align:center;">✨ Produit ajouté avec succès !</p>
        <?php endif; ?>

        <form action="../Controllers/ProduitController.php?action=ajouter" method="POST" enctype="multipart/form-data">

            <label>Nom du cosmétique :</label><br>
            <input type="text" name="nom" required style="width:100%; padding:8px; margin:8px 0;"><br>

            <label>Prix (DT) :</label><br>
            <input type="number" step="0.001" name="prix" required style="width:100%; padding:8px; margin:8px 0;"><br>

            <label>Quantité en stock :</label><br>
            <input type="number" name="stock" required style="width:100%; padding:8px; margin:8px 0;"><br>

            <label>Catégorie :</label><br>
            <?php
                // On charge les catégories dynamiquement depuis la base de données
                // comme dans admin_add.php (plus flexible que les options fixes)
                require_once '../Models/ProduitManager.php';
                $manager = new ProduitManager();
                $categories = $manager->getAllCategories();
            ?>
            <select name="id_categorie" style="width:100%; padding:8px; margin:8px 0;">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['idc']) ?>">
                        <?= htmlspecialchars($cat['nomc']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Image du produit :</label><br>
            <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.gif,.webp" required style="margin:8px 0;"><br><br>

            <button type="submit" style="width:100%; background:#333; color:white; padding:12px; border:none; border-radius:5px; cursor:pointer; font-size:1em;">
                Enregistrer le produit
            </button>
        </form>
    </div>
</body>
</html>
