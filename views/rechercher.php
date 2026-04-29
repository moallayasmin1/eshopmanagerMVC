<?php
// views/rechercher.php
// Vue muette - reçoit $products et $categories du controller
session_start();
require_once '../Models/ProduitManager.php';

$manager    = new ProduitManager();
$categories = $manager->getAllCategories();
$keyword    = $_GET['q']   ?? '';
$id_cat     = (int)($_GET['cat'] ?? 0);
$products   = $manager->search($keyword, $id_cat);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Recherche - FashionOnline</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <h1>Rechercher un produit</h1>

    <!-- Formulaire recherche multicritères -->
    <form action="" method="GET" style="text-align:center; margin:20px auto; max-width:600px;">
        <input type="text" name="q" placeholder="Nom du produit..." value="<?= htmlspecialchars($keyword) ?>" style="width:45%; padding:10px; margin:5px;">

        <select name="cat" style="padding:10px; margin:5px;">
            <option value="0">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['idc'] ?>" <?= $cat['idc'] == $id_cat ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nomc']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="padding:10px 20px; margin:5px;">🔍 Chercher</button>
    </form>

    <!-- Résultats -->
    <div class="container" style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px; padding:20px;">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <div class="card">
                    <small><?= htmlspecialchars($p['nomc'] ?? '') ?></small>
                    <img src="../public/images/<?= htmlspecialchars($p['image']) ?>" class="prod-img" alt="<?= htmlspecialchars($p['nomp']) ?>">
                    <h2><?= htmlspecialchars($p['nomp']) ?></h2>
                    <p style="font-weight:bold; color:#e91e63;"><?= number_format($p['prix'], 3) ?> DT</p>
                    <?php if ($p['stock'] > 0): ?>
                        <p style="color:green;">En stock (<?= $p['stock'] ?>)</p>
                    <?php else: ?>
                        <p style="color:red;">Rupture de stock</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php elseif (!empty($keyword) || $id_cat > 0): ?>
            <p>Aucun produit trouvé pour cette recherche.</p>
        <?php else: ?>
            <p>Entrez un mot-clé ou choisissez une catégorie pour rechercher.</p>
        <?php endif; ?>
    </div>
</body>
</html>
