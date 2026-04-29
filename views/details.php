<?php
// views/details.php
session_start();
require_once '../Models/ProduitManager.php';

$id = (int)($_GET['id'] ?? 0);
$manager = new ProduitManager();
$p = $manager->getById($id);

if (!$p) {
    header("Location: catalogue.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title><?= htmlspecialchars($p['nomp']) ?> - FashionLine</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div style="max-width:900px; margin:40px auto; padding:0 20px;">

        <!-- Fil d'ariane -->
        <p style="color:#B5838D; font-size:0.8em; margin-bottom:25px;">
            <a href="catalogue.php" style="color:#D4607A;">Catalogue</a>
            &nbsp;›&nbsp;
            <span style="color:#8A7A80;"><?= htmlspecialchars($p['nomc'] ?? '') ?></span>
            &nbsp;›&nbsp;
            <?= htmlspecialchars($p['nomp']) ?>
        </p>

        <!-- Contenu principal -->
        <div style="display:flex; gap:40px; flex-wrap:wrap; background:white; border:1.5px solid #F7D6E0; border-radius:20px; overflow:hidden; box-shadow:0 8px 30px rgba(201,64,112,0.1);">

            <!-- Image -->
            <div style="flex:1; min-width:280px;">
                <img src="../public/images/<?= htmlspecialchars($p['image']) ?>"
                     alt="<?= htmlspecialchars($p['nomp']) ?>"
                     style="width:100%; height:420px; object-fit:cover; display:block;">
            </div>

            <!-- Infos produit -->
            <div style="flex:1; min-width:280px; padding:35px 35px 35px 10px; display:flex; flex-direction:column; justify-content:center;">

                <!-- Catégorie -->
                <small style="color:#D4607A; font-size:0.7em; letter-spacing:3px; text-transform:uppercase; font-weight:600;">
                    <?= htmlspecialchars($p['nomc'] ?? 'Cosmétique') ?>
                </small>

                <!-- Nom -->
                <h1 style="font-family:'Playfair Display',serif; font-size:2em; color:#2A1F23; margin:10px 0; padding:0; text-align:left;">
                    <?= htmlspecialchars($p['nomp']) ?>
                </h1>
                <div style="width:50px; height:2px; background:linear-gradient(90deg,#D4607A,#C94070); margin-bottom:20px;"></div>

                <!-- Prix -->
                <p style="font-size:2em; font-weight:700; color:#C94070; font-family:'Playfair Display',serif; margin-bottom:15px;">
                    <?= number_format($p['prix'], 3) ?> DT
                </p>

                <!-- Description -->
                <?php if (!empty($p['descriptionp'])): ?>
                <div style="background:#FDF0F4; border-left:3px solid #D4607A; border-radius:0 10px 10px 0; padding:15px 18px; margin-bottom:20px;">
                    <p style="color:#2A1F23; font-size:0.9em; line-height:1.8; margin:0;">
                        <?= htmlspecialchars($p['descriptionp']) ?>
                    </p>
                </div>
                <?php else: ?>
                <div style="background:#FDF0F4; border-left:3px solid #F7D6E0; border-radius:0 10px 10px 0; padding:15px 18px; margin-bottom:20px;">
                    <p style="color:#B5838D; font-size:0.85em; font-style:italic; margin:0;">
                        Aucune description disponible pour ce produit.
                    </p>
                </div>
                <?php endif; ?>

                <!-- Stock -->
                <?php if ($p['stock'] > 0): ?>
                    <p style="color:#2E7D32; font-size:0.85em; margin-bottom:20px;">
                        ✅ En stock — <?= $p['stock'] ?> unité(s) disponible(s)
                    </p>
                    <!-- Bouton ajouter au panier -->
                    <a href="../Controllers/PanierController.php?action=ajouter&id=<?= $p['idp'] ?>"
                       style="display:inline-block; background:linear-gradient(135deg,#D4607A,#C94070); color:white; padding:14px 35px; border-radius:25px; text-decoration:none; font-size:0.85em; font-weight:600; letter-spacing:1.5px; box-shadow:0 4px 15px rgba(212,96,122,0.3); text-align:center; margin-bottom:12px;">
                        🛒 Ajouter au panier
                    </a>
                <?php else: ?>
                    <p style="color:#C94070; font-size:0.85em; margin-bottom:20px;">❌ Rupture de stock</p>
                    <button disabled style="opacity:0.5; cursor:not-allowed; margin:0 0 12px 0;">Indisponible</button>
                <?php endif; ?>

                <!-- Retour catalogue -->
                <a href="catalogue.php" style="color:#B5838D; font-size:0.8em; text-decoration:none;">
                    ← Retour au catalogue
                </a>

                <!-- Actions admin -->
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                <div style="margin-top:20px; padding-top:20px; border-top:1px solid #F7D6E0; display:flex; gap:15px;">
                    <a href="../Controllers/ProduitController.php?action=modifier&id=<?= $p['idp'] ?>"
                       style="color:#D4607A; font-size:0.8em;">✏️ Modifier</a>
                    <a href="../Controllers/ProduitController.php?action=supprimer&id=<?= $p['idp'] ?>"
                       style="color:red; font-size:0.8em;"
                       onclick="return confirm('Supprimer ce produit ?')">🗑️ Supprimer</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
