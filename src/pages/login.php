<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plateforme d'Entraide</title>
        <link href="./assets/css/signUp.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <main>
            <nav class="signup-form">
                <h2>Se connecter</h2>
                <p class="form-description">Connectez-vous à votre compte MainsTendues:</p>
                <form action="index.php?page=signUp" method="post">
                    <label for="email">Adresse e-mail :</label>
                    <input type="email" id="email" name="email" required placeholder="votre@email.com">
                    <label for="password">Mot de passe :</label>            
                    <input type="password" id="password" name="password" required placeholder="········">
                    <input type="submit" class="btn" value="Se connecter">
                </form>
                <p class="login-link">Pas de compte ?<a href="index.php?page=signUp">Créer un compte</a></p>
            </nav>
        </main>
    </body>
</html>