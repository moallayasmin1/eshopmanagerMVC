<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
        <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Connexion - FashionOnline</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
        <h2>Connexion</h2>
        <?php if (isset($_GET['err'])): ?>
            <p style="color: red;">Email walla mot de passe ghalet!</p>
        <?php endif; ?>

        <form action="../Controllers/UtilisateurController.php?action=login" method="POST">
            <label>Email:</label><br>
            <input type="email" name="email" required style="width: 100%;"><br><br>
            
            <label>Mot de passe:</label><br>
            <input type="password" name="mdp" required style="width: 100%;"><br><br>
            
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>