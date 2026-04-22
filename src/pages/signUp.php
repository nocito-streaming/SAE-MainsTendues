<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once  './src/db_config.php';
require_once  './src/auth.php';
require_once  './src/functions.php';

$error = null;
$showVerification = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['code_verification'])) {
        $userCode = trim($_POST['code'] ?? '');
        
        if (isset($_SESSION['temp_user']) && $userCode === $_SESSION['temp_user']['otp']) {
            
            $data = $_SESSION['temp_user'];
            $result = register_user($data['name'], $data['surname'], $data['email'], $data['pwd'], $data['tel']);
            
            if ($result === true) {
                unset($_SESSION['temp_user']);
                header("Location: index.php?page=login&success=account_created");
                exit;
            } else {
                $error = "Erreur lors de la création du compte : " . $result;
                $showVerification = true;
            }
        } else {
            $error = "Code de vérification incorrect ou expiré.";
            $showVerification = true;
        }
    } 
    else {
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
            $otp = str_pad((string)rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $_SESSION['temp_user'] = [
                'name' => $fName,
                'surname' => $sName,
                'email' => $email,
                'pwd' => $pwd,
                'tel' => $tel,
                'otp' => $otp
            ];

            if (sendVerificationCode($email, $otp)) {
                $showVerification = true;
            } else {
                $error = "Impossible d'envoyer l'email de validation. Veuillez réessayer.";
            }
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
            <?php if ($showVerification): ?>
                <div class="login-form-container">
                    <h2>Vérifiez votre messagerie</h2>
                    <p class="form-description">
                        Un code a été envoyé à <strong><?php echo htmlspecialchars($_SESSION['temp_user']['email'] ?? 'votre adresse'); ?></strong>.
                    </p>

                    <?php if ($error): ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=signUp" method="post" style="display: flex; flex-direction: column; align-items: center;">
                        <input type="hidden" name="code_verification" value="1">
                        
                        <div class="input-group" style="text-align: center; width: 100%; display: flex; flex-direction: column; align-items: center;">
                            <label for="code-verification" style="margin-bottom: 10px;">Entrez le code à 6 chiffres :</label>
                            <input 
                                type="text" 
                                id="code-verification" 
                                name="code" 
                                maxlength="6" 
                                pattern="\d{6}" 
                                inputmode="numeric" 
                                autocomplete="one-time-code"
                                required 
                                title="Veuillez entrer exactement 6 chiffres."
                                style="text-align: center; letter-spacing: 8px; font-size: 1.5em; width: 200px; padding: 10px; border: 2px solid #ccc; border-radius: 8px; outline: none;"
                                onfocus="this.style.borderColor='#2563eb';"
                                onblur="this.style.borderColor='#ccc';"
                            >
                        </div>
                        
                        <input type="submit" class="btn" value="Valider l'inscription" style="margin-top: 20px;">
                    </form>
                </div>

            <?php else: ?>
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
                                <input type="text" id="name" name="name" required placeholder="Votre prénom" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            </div>

                            <div class="input-group field">
                                <label for="surname">Nom :</label>
                                <input type="text" id="surname" name="surname" required placeholder="Votre nom" value="<?php echo htmlspecialchars($_POST['surname'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label for="email">Adresse e-mail :</label>
                            <input type="email" id="email" name="email" required placeholder="votre@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>

                        <div class="input-group">
                            <label for="tel">Numéro de téléphone :</label>
                            <input type="tel" id="tel" name="tel" placeholder="07 68 79 80 90" value="<?php echo htmlspecialchars($_POST['tel'] ?? ''); ?>">
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
            <?php endif; ?>
        </main>
    </body>
</html>