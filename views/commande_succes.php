<?php
// views/commande_succes.php
session_start();
require_once '../Models/CommandeManager.php';

$id_commande = (int)($_GET['id'] ?? 0);
$cm = new CommandeManager();
$commande = $cm->getCommande($id_commande);
$details  = $cm->getDetailsCommande($id_commande);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Commande confirmée - FashionLine</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div style="max-width:700px; margin:40px auto; padding:0 20px; text-align:center;">

        <!-- Succès -->
        <div style="background:white; border:1.5px solid #F7D6E0; border-radius:20px; padding:40px; box-shadow:0 4px 20px rgba(201,64,112,0.08); margin-bottom:30px;">
            <p style="font-size:4em; margin-bottom:10px;">🌸</p>
            <h1 style="color:#C94070; font-family:'Playfair Display',serif; margin-bottom:10px;">Commande confirmée !</h1>
            <p style="color:#8A7A80; margin-bottom:5px;">Merci pour votre commande</p>
            <p style="color:#D4607A; font-weight:600; font-size:1.1em;">N° <?= $id_commande ?></p>
            <?php if ($commande): ?>
                <p style="color:#8A7A80; font-size:0.85em; margin-top:10px;">
                    Date : <?= date('d/m/Y à H:i', strtotime($commande['date_commande'])) ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Détails commande -->
        <?php if (!empty($details)): ?>
        <div style="background:white; border:1.5px solid #F7D6E0; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(201,64,112,0.08); margin-bottom:30px;">
            <div style="background:linear-gradient(135deg,#D4607A,#C94070); padding:16px 20px;">
                <h2 style="color:white; font-size:0.9em; letter-spacing:2px; margin:0;">DÉTAILS DE LA COMMANDE</h2>
            </div>
            <?php foreach ($details as $item): ?>
            <div style="display:flex; align-items:center; gap:15px; padding:15px 20px; border-bottom:1px solid #F7D6E0;">
                <img src="../public/images/<?= htmlspecialchars($item['image']) ?>"
                     style="width:55px; height:55px; object-fit:cover; border-radius:10px;">
                <div style="flex:1; text-align:left;">
                    <p style="font-weight:600; color:#2A1F23; margin:0;"><?= htmlspecialchars($item['nomp']) ?></p>
                    <small style="color:#8A7A80;">Qté : <?= $item['quantite'] ?></small>
                </div>
                <p style="color:#C94070; font-weight:600; margin:0;">
                    <?= number_format($item['prix_unitaire'] * $item['quantite'], 3) ?> DT
                </p>
            </div>
            <?php endforeach; ?>
            <!-- Total -->
            <?php if ($commande): ?>
            <div style="padding:16px 20px; text-align:right; background:#FDF0F4;">
                <span style="font-size:1.2em; font-weight:700; color:#C94070; font-family:'Playfair Display',serif;">
                    Total : <?= number_format($commande['total'], 3) ?> DT
                </span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div style="display:flex; gap:15px; justify-content:center; flex-wrap:wrap;">
            <a href="historique.php"
               style="background:white; border:1.5px solid #D4607A; color:#D4607A; padding:12px 28px; border-radius:25px; text-decoration:none; font-size:0.8em; font-weight:600; letter-spacing:1px;">
                📋 Voir mes commandes
            </a>
            <a href="catalogue.php"
               style="background:linear-gradient(135deg,#D4607A,#C94070); color:white; padding:12px 28px; border-radius:25px; text-decoration:none; font-size:0.8em; font-weight:600; letter-spacing:1px;">
                🛍️ Continuer les achats
            </a>
        </div>
    </div>
</body>
</html>
