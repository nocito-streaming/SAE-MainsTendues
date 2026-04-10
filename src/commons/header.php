<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MainsTendues est la plateforme gratuite d'entraide locale. Demandez de l'aide pour vos courses et petits travaux ou devenez bénévole pour aider vos voisins.">
    <title>Plateforme d'Entraide</title>
    <link href="./src/commons/header.css" rel="stylesheet" type="text/css"/>
    <link href="./assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="./assets/images/logo.png" type="image/x-icon">
</head>
<body>
    <header class="main-header">
        <div class="logo-container">
            <a href="index.php?page=home" class="logo-link">
                <img src="./assets/images/LogoMainsTendues.png" alt="Logo Mains Tendues" class="logo-img"/>
                <div class="logo-text">
                    <h1>Mains Tendues</h1>
                    <p>Main dans la main, pour un meilleur demain</p>
                </div>
            </a>
        </div>

        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav id="nav-menu">
            <a href="index.php?page=home">Accueil</a>
            <a href="index.php?page=requestHelp">Demander de l'aide</a>
            <a href="index.php?page=offerHelp">Proposer de l'aide</a>
            <?php if (isset($_SESSION['user_id'])):
                if ($_SESSION['is_admin'] === true ): ?>
                    <a href="index.php?page=profileADM">Profile d'Administrateur</a>
                <?php else: ?>
                    <a href="index.php?page=profile">Profile</a>
                <?php endif?>
                <a href="index.php?page=logout">Quit</a>
            <?php else: ?>
                <a href="index.php?page=login">Se connecter</a>
                <a href="index.php?page=signUp" class="btn-signup">Créer un compte</a>
            <?php endif ?>
        </nav>
    </header>

    <script>
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('nav-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>