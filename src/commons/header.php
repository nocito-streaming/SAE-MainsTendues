<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme d'Entraide</title>
    <link href="./src/commons/header.css" rel="stylesheet" type="text/css"/>
    <link href="./assets/css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <header class="main-header">
        <div class="logo-container">
            <img src="./assets/images/LogoMainsTendues.png" alt="Logo Mains Tendues" class="logo-img"/>
            <div class="logo-text">
                <h1>Mains Tendues</h1>
                <p>Main dans la main, pour un meilleur demain</p>
            </div>
        </div>

        <nav>
            <a href="index.php?page=home">Accueil</a>
            <a href="index.php?page=requestHelp">Demander de l'aide</a>
            <a href="index.php?page=offerHelp">Proposer de l'aide</a>
            <?php if (isset($_SESSION['user_id'])):
                if ($_SESSION['is_admin'] === true ): ?>
                    <a href="index.php?page=profileADM">Profile d'Administrateur</a>
                <?php else: ?>
                    <a href="index.php?page=profile">Profile</a>
                <?php endif?>
                <a href="index.php?page=logout" >Quit</a>
            <?php else: ?>
                <a href="index.php?page=login">Se connecter</a>
                <a href="index.php?page=signUp" class="btn-signup">Créer un compte</a>
            <?php endif ?>
        </nav>
    </header>
</body>
</html>