<?php
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
    <title>FashionLine - Catalogue</title>
</head>
<body>
    <?php include 'menu.php'; ?>
 
    <h1>Nos Produits Cosmétiques</h1>
 
    <?php if (isset($_GET['added'])): ?>
        <p style="color:#2E7D32; text-align:center; background:#f0fff0; padding:12px; border-radius:10px; max-width:400px; margin:10px auto;">✅ Produit ajouté au panier !</p>
    <?php endif; ?>
    <?php if (isset($_GET['err']) && $_GET['err'] == 'stock'): ?>
        <p style="color:#C94070; text-align:center;">Ce produit est en rupture de stock.</p>
    <?php endif; ?>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color:#2E7D32; text-align:center;">✨ Produit ajouté avec succès !</p>
    <?php endif; ?>
    <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
        <p style="color:#2E7D32; text-align:center;">✅ Produit modifié avec succès !</p>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <p style="color:#2E7D32; text-align:center;">🗑️ Produit supprimé.</p>
    <?php endif; ?>
 
    <div class="container" style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px; padding:20px;">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <div class="card">
                    <small><?= htmlspecialchars($p['nomc'] ?? 'Général', ENT_QUOTES, 'UTF-8') ?></small>
 
                    <img src="../public/images/<?= htmlspecialchars($p['image']) ?>"
                         class="prod-img" alt="<?= htmlspecialchars($p['nomp']) ?>">
 
                    <a href="details.php?id=<?= $p['idp'] ?>" style="text-decoration:none; color:inherit;"><h2><?= htmlspecialchars($p['nomp']) ?></h2></a>
 
                    <p style="font-weight:bold; color:#e91e63;"><?= number_format($p['prix'], 3) ?> DT</p>
 
                    <?php if ($p['stock'] > 0): ?>
                        <p style="color:green;">En stock (<?= $p['stock'] ?>)</p>
                        <!-- Bouton Ajouter au panier -->
                        <a href="../Controllers/PanierController.php?action=ajouter&id=<?= $p['idp'] ?>"
                           style="display:inline-block; background:linear-gradient(135deg,#D4607A,#C94070); color:white; border:none; padding:10px 20px; cursor:pointer; border-radius:25px; text-decoration:none; font-size:0.75em; font-weight:600; letter-spacing:1px; margin:8px 0 15px;">
                            🛒 Ajouter au panier
                        </a>
                    <?php else: ?>
                        <p style="color:red;">Rupture de stock</p>
                        <button disabled>Indisponible</button>
                    <?php endif; ?>
 
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <hr>
                        <div style="padding:10px 0; display:flex; justify-content:center; gap:15px;">
                            <a href="../Controllers/ProduitController.php?action=modifier&id=<?= $p['idp'] ?>"
                               style="color:#D4607A; font-size:0.8em; text-decoration:none;">✏️ Modifier</a>
                            <a href="../Controllers/ProduitController.php?action=supprimer&id=<?= $p['idp'] ?>"
                               style="color:red; font-size:0.8em; text-decoration:none;"
                               onclick="return confirm('Supprimer ce produit ?')">🗑️ Supprimer</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit disponible.</p>
        <?php endif; ?>
    </div>
</body>
</html>
 