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
                <h2>Créer un compte</h2>
                <p class="form-description">Veuillez remplir les informations suivantes:</p>
                <form action="index.php?page=signUp" method="post">
                    <div class="row">
                        <div class="field">
                            <label for="name">Prénom :</label>
                            <input type="text" id="name" name="name" required placeholder="votre prénom">
                        </div>

                        <div class="field">
                            <label for="surname">Nom :</label>
                            <input type="text" id="surname" name="surname" required placeholder="votre nom">
                        </div>
                    </div>
                    <label for="email">Adresse e-mail :</label>
                    <input type="email" id="email" name="email" required placeholder="votre@email.com">
                    <label for="tel">Numéro de téléphone :</label>
                    <input type="tel" id="tel" name="tel" placeholder="07 68 79 80 90">
                    <label for="password">Mot de passe :</label>            
                    <input type="password" id="password" name="password" required placeholder="········">
                    <label for="confirm">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm" name="confirm" required placeholder="········">
                    <input type="submit" class="btn" value="Créer mon compte">
                </form>
                <p class="login-link">Déjà un compte ? <a href="index.php?page=login">Se connecter</a></p>
            </nav>
        </main>
    </body>
</html>