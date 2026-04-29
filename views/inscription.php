<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
       <link rel="stylesheet" href="../assetsstyle/style123.css">
    <title>Inscription - FashionOnline</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        <h2>Créer un compte</h2>
        <form action="../Controllers/UtilisateurController.php?action=inscription" method="POST">
            <label>Nom complet:</label><br>
            <input type="text" name="nomu" required style="width: 100%; padding: 8px; margin: 10px 0;"><br>
            
            <label>Email:</label><br>
            <input type="email" name="email" required style="width: 100%; padding: 8px; margin: 10px 0;"><br>
            
            <label>Mot de passe:</label><br>
            <input type="password" name="mdp" required style="width: 100%; padding: 8px; margin: 10px 0;"><br>
            
            <button type="submit" style="width: 100%; background: #4CAF50; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">S'inscrire</button>
        </form>
        <p style="text-align:center; font-size: 0.9em;">Déjà inscrit ? <a href="connexion.php">Connectez-vous</a></p>
    </div>
</body>
</html>