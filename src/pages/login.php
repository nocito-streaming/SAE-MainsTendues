<?php
require_once  './src/db_config.php';
require_once  './src/auth.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    if (login_user($email, $password)) {
        header('Location: index.php?page=profile');
        if ($_SESSION["is_admin"]){
            header('location: index.php?page=profileADM');
        }
        die();
    }
    else {
        $error = "Email ou mot de passe incorrecte";
    }

}
?>

<!-- TO SHOW THE ERRORS DURING DÉVELOPPEMENT. DELETE IT-->
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
<!-- TO SHOW THE ERRORS DURING DÉVELOPPEMENT. DELETE IT-->


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plateforme d'Entraide</title>
        <link href="./assets/css/signUp.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <section class="hero">
        <div class="hero-content">
            <h1>Bon retour sur MainsTendues !</h1>
            <p>Une plateforme pour s'entraider et partager.</p>
        </div>
    </section>
        <main>
            <nav class="signup-form">
                <h2>Se connecter</h2>
                <p class="form-description">Connectez-vous à votre compte MainsTendues:</p>
                <form action="index.php?page=login" method="post">
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