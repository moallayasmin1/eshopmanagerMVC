<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php $nbPanier = isset($_SESSION['panier']) ? array_sum(array_column($_SESSION['panier'], 'quantite')) : 0; ?>
<nav style="background:#333; padding:10px; display:flex; gap:20px; align-items:center;">
    <a href="catalogue.php" style="color:white;">Catalogue</a>
    <a href="rechercher.php" style="color:white;">Recherche</a>
 
    <!-- Icône Panier -->
    <a href="panier.php" style="color:white; position:relative;">
        🛒 Panier
        <?php if ($nbPanier > 0): ?>
            <span style="position:absolute; top:-8px; right:-12px; background:#C94070; color:white; border-radius:50%; width:18px; height:18px; font-size:0.65em; display:flex; align-items:center; justify-content:center; font-weight:700;">
                <?= $nbPanier ?>
            </span>
        <?php endif; ?>
    </a>
 
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="historique.php" style="color:white;">📋 Mes commandes</a>
        <span style="color:#aaa; margin-left:auto;">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
        <a href="../Controllers/UtilisateurController.php?action=logout" style="color:white;">Déconnexion</a>
        <?php if ($_SESSION['user_role'] == 'admin'): ?>
            <a href="ajouter_produit.php" style="color:yellow;">+ Ajouter Produit</a>
        <?php endif; ?>
    <?php else: ?>
        <span style="margin-left:auto;"></span>
        <a href="connexion.php" style="color:white;">Connexion</a>
        <a href="inscription.php" style="color:white;">Inscription</a>
    <?php endif; ?>
</nav>