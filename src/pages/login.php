<?php
require_once  './src/db_config.php';
require_once  './src/auth.php';

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    
    if (login_user($email, $password)) {
        // Redirection plus propre avec if/else
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
            header('Location: index.php?page=profileADM');
        } else {
            header('Location: index.php?page=profile');
        }
        exit(); // Toujours utiliser exit() ou die() après un header
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion - MainsTendues</title>
        <link rel="stylesheet" href="assets/css/variables.css">
        <link href="./assets/css/signUp.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="page-header">
            <div class="header-content">
                <h2>Bon retour sur MainsTendues !</h2>
                <p>Une plateforme pour s'entraider et partager.</p>
            </div>
        </div>
        <main>
            <div class="login-form-container signup-container">
                <h2>Se connecter</h2>
                <p class="form-description">Connectez-vous à votre compte MainsTendues :</p>
                
                <?php if ($error): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=login" method="post">
                    <div class="input-group">
                        <label for="email">Adresse e-mail :</label>
                        <input type="email" id="email" name="email" required placeholder="votre@email.com">
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Mot de passe :</label>            
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>
                    
                    <input type="submit" class="btn" value="Se connecter">
                </form>
                
                <p class="login-link">Pas de compte ? <a href="index.php?page=signUp">Créer un compte</a></p>
            </div>
        </main>
    </body>
</html>