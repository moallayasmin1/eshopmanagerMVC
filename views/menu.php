<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav style="background:#333; padding:10px; display:flex; gap:20px;">
    <a href="catalogue.php" style="color:white;">Catalogue</a>
    <a href="rechercher.php" style="color:white;">Recherche</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <span style="color:#aaa;">Bonjour, <?= $_SESSION['user_nom'] ?></span>
        <a href="../Controllers/UtilisateurController.php?action=logout" style="color:white;">Déconnexion</a>
        <?php if ($_SESSION['user_role'] == 'admin'): ?>
            <a href="ajouter_produit.php" style="color:yellow;">+ Ajouter Produit</a>
        <?php endif; ?>
    <?php else: ?>
        <a href="connexion.php" style="color:white;">Connexion</a>
        <a href="inscription.php" style="color:white;">Inscription</a>
    <?php endif; ?>
</nav>