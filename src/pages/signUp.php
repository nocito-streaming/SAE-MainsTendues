<?php
require_once  './src/db_config.php';
require_once  './src/auth.php';
require_once  './src/functions.php';

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fName = trim($_POST["name"] ?? '');
    $sName = trim($_POST["surname"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $pwd = trim($_POST["password"] ?? '');
    $tel = trim($_POST["tel"] ?? '');
    $confirm = trim($_POST["confirm"] ?? '');

    if ($pwd !== $confirm) {
        $error  = "Les mots de passe sont différents.";
    } elseif (empty($fName) || empty($sName) || empty($email) || empty($confirm) || empty($pwd)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        $result = register_user($fName, $sName, $email, $pwd, $tel);
        if ($result === true) {
            header("Location: index.php?page=login");
            exit;
        } else {
            $error = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription - MainsTendues</title>
        <link href="./assets/css/signUp.css" rel="stylesheet" type="text/css"/>
    </head> 
    <body>
        <div class="page-header">
            <div class="header-content">
                <h2>Créer un compte en quelques clics.</h2>
                <p>Finalisez la création de votre compte pour profiter de tous nos services.</p>
            </div>
        </div>
        <main>
            <div class="login-form-container signup-container">
                <h2>Créer un compte</h2>
                <p class="form-description">Veuillez remplir les informations suivantes :</p>
                
                <?php if ($error): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=signUp" method="post">
                    <div class="row">
                        <div class="input-group field">
                            <label for="name">Prénom :</label>
                            <input type="text" id="name" name="name" required placeholder="Votre prénom">
                        </div>

                        <div class="input-group field">
                            <label for="surname">Nom :</label>
                            <input type="text" id="surname" name="surname" required placeholder="Votre nom">
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="email">Adresse e-mail :</label>
                        <input type="email" id="email" name="email" required placeholder="votre@email.com">
                    </div>

                    <div class="input-group">
                        <label for="tel">Numéro de téléphone :</label>
                        <input type="tel" id="tel" name="tel" placeholder="07 68 79 80 90">
                    </div>

                    <div class="row">
                        <div class="input-group field">
                            <label for="password">Mot de passe :</label>            
                            <input type="password" id="password" name="password" required placeholder="••••••••">
                        </div>
                        <div class="input-group field">
                            <label for="confirm">Confirmer :</label>
                            <input type="password" id="confirm" name="confirm" required placeholder="••••••••">
                        </div>
                    </div>

                    <input type="submit" class="btn" value="Créer mon compte">
                </form>
                
                <p class="login-link">Déjà un compte ? <a href="index.php?page=login">Se connecter</a></p>
            </div>
        </main>
    </body>
</html>