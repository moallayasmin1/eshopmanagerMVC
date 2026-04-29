<?php
// views/panier.php
session_start();
$panier = $_SESSION['panier'] ?? [];
$total  = 0;
foreach ($panier as $item) {
    $total += $item['prix'] * $item['quantite'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Mon Panier - FashionLine</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <h1>🛒 Mon Panier</h1>

    <?php if (isset($_GET['err']) && $_GET['err'] == 'vide'): ?>
        <p style="color:red; text-align:center;">Votre panier est vide !</p>
    <?php endif; ?>
    <?php if (isset($_GET['err']) && $_GET['err'] == 'commande'): ?>
        <p style="color:red; text-align:center;">Erreur lors de la commande, réessayez.</p>
    <?php endif; ?>

    <div style="max-width:900px; margin:30px auto; padding:0 20px;">

        <?php if (empty($panier)): ?>
            <div style="text-align:center; padding:60px 20px;">
                <p style="font-size:4em;">🛒</p>
                <p style="color:#B5838D; font-size:1.2em; margin:20px 0;">Votre panier est vide</p>
                <a href="catalogue.php" style="background:linear-gradient(135deg,#D4607A,#C94070); color:white; padding:12px 30px; border-radius:25px; text-decoration:none; font-size:0.85em; letter-spacing:1.5px;">
                    Voir le catalogue
                </a>
            </div>

        <?php else: ?>

            <!-- Tableau panier -->
            <table style="width:100%; border-collapse:collapse; background:white; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(201,64,112,0.08);">
                <thead>
                    <tr style="background:linear-gradient(135deg,#D4607A,#C94070);">
                        <th style="padding:16px 20px; color:white; text-align:left; font-size:0.8em; letter-spacing:1.5px;">PRODUIT</th>
                        <th style="padding:16px 20px; color:white; text-align:center; font-size:0.8em; letter-spacing:1.5px;">PRIX</th>
                        <th style="padding:16px 20px; color:white; text-align:center; font-size:0.8em; letter-spacing:1.5px;">QUANTITÉ</th>
                        <th style="padding:16px 20px; color:white; text-align:center; font-size:0.8em; letter-spacing:1.5px;">SOUS-TOTAL</th>
                        <th style="padding:16px 20px; color:white; text-align:center; font-size:0.8em; letter-spacing:1.5px;">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($panier as $idp => $item): ?>
                    <tr style="border-bottom:1px solid #F7D6E0;">
                        <!-- Produit -->
                        <td style="padding:16px 20px;">
                            <div style="display:flex; align-items:center; gap:15px;">
                                <img src="../public/images/<?= htmlspecialchars($item['image']) ?>"
                                     style="width:60px; height:60px; object-fit:cover; border-radius:10px; border:1px solid #F7D6E0;">
                                <div>
                                    <p style="font-weight:600; color:#2A1F23; margin:0;"><?= htmlspecialchars($item['nomp']) ?></p>
                                    <small style="color:#D4607A; font-size:0.7em; letter-spacing:1px; text-transform:uppercase;"><?= htmlspecialchars($item['nomc']) ?></small>
                                </div>
                            </div>
                        </td>

                        <!-- Prix unitaire -->
                        <td style="padding:16px 20px; text-align:center; color:#C94070; font-weight:600;">
                            <?= number_format($item['prix'], 3) ?> DT
                        </td>

                        <!-- Quantité modifiable -->
                        <td style="padding:16px 20px; text-align:center;">
                            <form action="../Controllers/PanierController.php?action=modifier" method="POST" style="display:inline;">
                                <input type="hidden" name="idp" value="<?= $idp ?>">
                                <div style="display:flex; align-items:center; justify-content:center; gap:8px;">
                                    <button type="submit" name="quantite" value="<?= $item['quantite'] - 1 ?>"
                                            style="width:30px; height:30px; border-radius:50%; background:#F7D6E0; border:none; color:#C94070; font-size:1.1em; cursor:pointer; margin:0; padding:0;">−</button>
                                    <span style="font-weight:600; min-width:20px; text-align:center;"><?= $item['quantite'] ?></span>
                                    <button type="submit" name="quantite" value="<?= $item['quantite'] + 1 ?>"
                                            style="width:30px; height:30px; border-radius:50%; background:#F7D6E0; border:none; color:#C94070; font-size:1.1em; cursor:pointer; margin:0; padding:0;">+</button>
                                </div>
                            </form>
                        </td>

                        <!-- Sous-total -->
                        <td style="padding:16px 20px; text-align:center; font-weight:600; color:#2A1F23;">
                            <?= number_format($item['prix'] * $item['quantite'], 3) ?> DT
                        </td>

                        <!-- Supprimer -->
                        <td style="padding:16px 20px; text-align:center;">
                            <a href="../Controllers/PanierController.php?action=supprimer&id=<?= $idp ?>"
                               style="color:#ccc; font-size:1.3em; text-decoration:none;"
                               onclick="return confirm('Supprimer ce produit du panier ?')"
                               title="Supprimer">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Récapitulatif et actions -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:30px; flex-wrap:wrap; gap:20px;">

                <!-- Vider panier -->
                <a href="../Controllers/PanierController.php?action=vider"
                   style="color:#B5838D; font-size:0.8em; letter-spacing:1px; text-decoration:none;"
                   onclick="return confirm('Vider tout le panier ?')">
                    🗑️ Vider le panier
                </a>

                <!-- Total + Commander -->
                <div style="background:white; border:1.5px solid #F7D6E0; border-radius:16px; padding:25px 35px; text-align:right; box-shadow:0 4px 20px rgba(201,64,112,0.08);">
                    <p style="color:#8A7A80; font-size:0.8em; letter-spacing:1px; margin-bottom:8px;">TOTAL</p>
                    <p style="font-size:2em; font-weight:700; color:#C94070; font-family:'Playfair Display', serif; margin-bottom:20px;">
                        <?= number_format($total, 3) ?> DT
                    </p>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../Controllers/PanierController.php?action=commander"
                           style="display:block; background:linear-gradient(135deg,#D4607A,#C94070); color:white; padding:14px 35px; border-radius:25px; text-decoration:none; font-size:0.8em; font-weight:600; letter-spacing:1.5px; text-align:center; box-shadow:0 4px 15px rgba(212,96,122,0.3);">
                            ✨ Passer la commande
                        </a>
                    <?php else: ?>
                        <a href="connexion.php?msg=login_required"
                           style="display:block; background:linear-gradient(135deg,#D4607A,#C94070); color:white; padding:14px 35px; border-radius:25px; text-decoration:none; font-size:0.8em; font-weight:600; letter-spacing:1.5px; text-align:center;">
                            🔒 Se connecter pour commander
                        </a>
                    <?php endif; ?>

                    <a href="catalogue.php" style="display:block; margin-top:12px; color:#B5838D; font-size:0.75em; text-align:center;">
                        ← Continuer les achats
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
