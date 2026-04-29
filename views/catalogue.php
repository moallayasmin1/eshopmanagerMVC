<?php
// views/catalogue.php
// Vue "muette" : reçoit les données du controller et affiche seulement
session_start();
require_once '../Models/ProduitManager.php';

$manager  = new ProduitManager();
$products = $manager->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>FashionOnline - Catalogue</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <h1>Nos Produits Cosmétiques</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color:green; text-align:center;">✨ Produit ajouté avec succès !</p>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
        <p style="color:green; text-align:center;">✅ Produit modifié avec succès !</p>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <p style="color:green; text-align:center;">🗑️ Produit supprimé.</p>
    <?php endif; ?>

    <div class="container" style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px; padding:20px;">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <div class="card">
                    <small><?= htmlspecialchars($p['nomc'] ?? 'Général') ?></small>

                    <img src="../public/images/<?= htmlspecialchars($p['image']) ?>" class="prod-img" alt="<?= htmlspecialchars($p['nomp']) ?>">

                    <h2><?= htmlspecialchars($p['nomp']) ?></h2>

                    <p style="font-weight:bold; color:#e91e63;"><?= number_format($p['prix'], 3) ?> DT</p>

                    <?php if ($p['stock'] > 0): ?>
                        <p style="color:green;">En stock (<?= $p['stock'] ?>)</p>
                        <button>Acheter</button>
                    <?php else: ?>
                        <p style="color:red;">Rupture de stock</p>
                        <button disabled>Indisponible</button>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <hr>
                        <a href="../Controllers/ProduitController.php?action=modifier&id=<?= $p['idp'] ?>"
                           style="color:#D4607A; font-size:0.85em;">✏️ Modifier</a>
                        &nbsp;|&nbsp;
                        <a href="../Controllers/ProduitController.php?action=supprimer&id=<?= $p['idp'] ?>"
                           style="color:red; font-size:0.85em;"
                           onclick="return confirm('Supprimer ce produit ?')">🗑️ Supprimer</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit disponible.</p>
        <?php endif; ?>
    </div>
</body>
</html>
