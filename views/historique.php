<?php
// views/historique.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php?msg=login_required");
    exit;
}

require_once '../Models/CommandeManager.php';
$cm = new CommandeManager();
$commandes = $cm->getCommandesUtilisateur($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Mes Commandes - FashionLine</title>
</head>
<body>
    <?php include 'menu.php'; ?>

    <h1>📋 Mes Commandes</h1>

    <div style="max-width:900px; margin:30px auto; padding:0 20px;">

        <?php if (empty($commandes)): ?>
            <div style="text-align:center; padding:60px 20px; background:white; border-radius:16px; border:1.5px solid #F7D6E0;">
                <p style="font-size:3em;">📦</p>
                <p style="color:#B5838D; font-size:1.1em; margin:15px 0;">Vous n'avez pas encore passé de commande</p>
                <a href="catalogue.php" style="background:linear-gradient(135deg,#D4607A,#C94070); color:white; padding:12px 28px; border-radius:25px; text-decoration:none; font-size:0.8em; font-weight:600;">
                    Voir le catalogue
                </a>
            </div>

        <?php else: ?>
            <?php foreach ($commandes as $cmd): ?>

            <!-- Accordion commande -->
            <div style="background:white; border:1.5px solid #F7D6E0; border-radius:16px; margin-bottom:20px; overflow:hidden; box-shadow:0 4px 20px rgba(201,64,112,0.06);">

                <!-- En-tête commande -->
                <div style="display:flex; justify-content:space-between; align-items:center; padding:18px 25px; background:#FDF0F4; flex-wrap:wrap; gap:10px;">
                    <div>
                        <p style="font-weight:700; color:#2A1F23; margin:0; font-size:1em;">
                            Commande N° <?= $cmd['idcm'] ?>
                        </p>
                        <small style="color:#8A7A80;">
                            <?= date('d/m/Y à H:i', strtotime($cmd['date_commande'])) ?>
                        </small>
                    </div>
                    <div style="text-align:center;">
                        <?php
                            $statuts = [
                                'en_attente' => ['🕐 En attente', '#E65100'],
                                'paye'       => ['✅ Payé',       '#2E7D32'],
                                'expedie'    => ['🚚 Expédié',    '#1565C0'],
                                'livre'      => ['📦 Livré',      '#2E7D32'],
                                'annule'     => ['❌ Annulé',     '#C94070'],
                            ];
                            $s = $statuts[$cmd['statut']] ?? ['❓', '#888'];
                        ?>
                        <span style="color:<?= $s[1] ?>; font-weight:600; font-size:0.85em;"><?= $s[0] ?></span>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:1.3em; font-weight:700; color:#C94070; font-family:'Playfair Display',serif; margin:0;">
                            <?= number_format($cmd['total'], 3) ?> DT
                        </p>
                    </div>
                </div>

                <!-- Détails produits -->
                <?php
                    $cm2 = new CommandeManager();
                    $details = $cm2->getDetailsCommande($cmd['idcm']);
                ?>
                <?php foreach ($details as $item): ?>
                <div style="display:flex; align-items:center; gap:15px; padding:12px 25px; border-top:1px solid #F7D6E0;">
                    <img src="../public/images/<?= htmlspecialchars($item['image']) ?>"
                         style="width:50px; height:50px; object-fit:cover; border-radius:8px; border:1px solid #F7D6E0;">
                    <div style="flex:1;">
                        <p style="font-weight:600; color:#2A1F23; margin:0; font-size:0.9em;"><?= htmlspecialchars($item['nomp']) ?></p>
                        <small style="color:#8A7A80;">Qté : <?= $item['quantite'] ?> × <?= number_format($item['prix_unitaire'], 3) ?> DT</small>
                    </div>
                    <p style="color:#C94070; font-weight:600; margin:0; font-size:0.9em;">
                        <?= number_format($item['prix_unitaire'] * $item['quantite'], 3) ?> DT
                    </p>
                </div>
                <?php endforeach; ?>
            </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
