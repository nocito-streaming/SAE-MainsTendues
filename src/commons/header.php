<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MainsTendues est la plateforme gratuite d'entraide locale. Demandez de l'aide pour vos courses et petits travaux ou devenez bénévole pour aider vos voisins.">
    <link rel="icon" type="image/webp" href="/assets/images/LogoMainsTendues.webp">
    <title>Plateforme d'Entraide</title>
    <style>
        :root {
            /* COULEURS PRINCIPALES */
            --primary-green: #2D6A4F;
            --primary-green-f: #24c87f;
            --dark-green: #1D8348;      /* Sert aussi pour les hovers au lieu de --hover-green */
            --primary-blue: #2E86C1;
            --primary-red: #E5243B;   
            --white: #ffffff;

            /* TEXTES & FONDS (Neutres) */
            --text-dark: #0f172a;       /* Titres forts ET fond de la barre latérale Admin */
            --text-main: #1e293b;       /* Texte standard de lecture */
            --text-muted: #64748b;      /* Descriptions, sous-titres, dates, icônes */
            
            --bg-body: #f1f5f9;         /* Unifie le fond des pages publiques et Admin */
            --bg-light: #f8fafc;        /* Fond des cartes, inputs et entêtes tableaux */
            --bg-dark-hover: #334155;   /* Survol dans la sidebar ET bordures sombres */

            --border-color: #e2e8f0;    /* Unifie toutes les bordures claires (inputs, tableaux) */

            /* ALERTES & BADGES */
            /* Succès */
            --success-bg: #dcfce7;
            --success-text: #16a34a;    /* Sert aussi pour les bordures de succès */
            
            /* Erreur / Urgent (Utilise --primary-red pour le texte) */
            --error-bg: #fee2e2;        /* Unifie fond d'erreur ET badge rouge */

            /* Badges Infos */
            --badge-blue-bg: #e0f2fe;
            --badge-blue-text: #0369a1;

            /* OMBRES & EFFETS */
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.08);
            
            --focus-ring: 0 0 0 3px rgba(40, 180, 99, 0.2); /* Effet contour sur les inputs */
            --gradient-primary: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        }

        * {
            margin: 0;
            padding: 0; 
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            height: 80vh;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--white);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo-img {
            width: 60px;
            height: auto;
            margin-right: 15px;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-text h1 {
            font-size: 24px;
            color: #2E86C1;
            margin: 0;
        }

        .logo-text p {
            font-size: 12px;
            font-style: italic;
            color: #666;
        }

        header nav a {
            text-decoration: none;
            color:  #2c3e50;
            margin-left: 20px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        header nav a:hover {
            color: #28B463;
        }

        .btn-signup {
            background-color: #093e62;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            flex-direction: column;
            gap: 5px;
            z-index: 1001;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background-color: #2c3e50;
            border-radius: 3px;
            transition: all 0.3s ease-in-out;
        }

        .logo-link {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        @media screen and (max-width: 1110px) {
            .hamburger {
                display: flex;
            }

            #nav-menu {
                position: absolute;
                top: 100%;
                left: -100%;
                width: 100%;
                background-color: white;
                flex-direction: column;
                text-align: center;
                transition: 0.3s;
                box-shadow: 0 10px 10px rgba(0,0,0,0.1);
                padding: 20px 0;
                display: flex;
            }

            #nav-menu.active {
                left: 0; 
            }

            header nav a {
                margin: 15px 0;
                display: block;
            }

            .btn-signup {
                display: inline-block;
                margin: 15px auto;
            }

            .hamburger.active span:nth-child(1) {
                transform: translateY(8px) rotate(45deg);
            }
            .hamburger.active span:nth-child(2) {
                opacity: 0;
            }
            .hamburger.active span:nth-child(3) {
                transform: translateY(-8px) rotate(-45deg);
            }
        }


        /* home.css */

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-main);
            line-height: 1.6;
            background-color: var(--white);
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .heroAccueil {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 550px;
            background: linear-gradient(135deg, rgba(46, 134, 193, 0.9), rgba(40, 180, 99, 0.9));
            color: var(--white);
            padding: 60px 20px;
        }

        .hero-content h1 {
            font-size: 4rem;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .hero-content h3 {
            font-weight: 400;
            font-size: 1.8rem;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .hero-content p {
            max-width: 750px;
            margin: 0 auto 40px;
            font-size: 1.2rem;
            line-height: 1.8;
        }

        .BOUBOU {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-button {
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .cta-button.primary {
            background-color: var(--white);
            color: var(--primary-green);
            box-shadow: var(--shadow-sm);
        }

        .cta-button.secondary {
            background-color: transparent;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .cta-button:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .cta-button.primary:hover {
            background-color: var(--bg-light);
        }

        .cta-button.secondary:hover {
            background-color: var(--white);
            color: var(--primary-green);
        }

        .stats-section {
            background-color: var(--white);
            padding: 40px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-blue);
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .features {
            padding: 80px 0;
            background-color: var(--bg-light);
            text-align: center;
        }

        .features h2 {
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: var(--primary-blue);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .step-card {
            background: var(--white);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.4s ease;
            border-bottom: 4px solid transparent;
        }

        .step-card:hover { 
            transform: translateY(-10px);
            border-bottom: 4px solid var(--primary-green);
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background: var(--badge-blue-bg);
            color: var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
        }

        .icon-circle svg {
            width: 40px;          
            height: 40px;         
            fill: currentColor;  
            display: block;
        }

        .step-card:hover .icon-circle {
            background: var(--primary-green);
            color: var(--white);
            transition: all 0.3s ease;
        }

        .step-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

        .step-card p {
            color: var(--text-muted);
        }

        .testimonials {
            padding: 80px 0;
            background-color: var(--white);
            text-align: center;
        }

        .testimonials h2 {
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: var(--primary-blue);
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }

        .testimonial-card {
            background: var(--bg-light);
            padding: 40px;
            border-radius: 16px;
            text-align: left;
            position: relative;
        }

        .quote-icon {
            font-size: 2rem;
            color: var(--border-color);
            margin-bottom: 15px;
        }

        .quote-icon svg {
            width: 2.5rem;     
            height: 2.5rem;
            fill: var(--border-color);
            margin-bottom: 15px;
            opacity: 0.6;
        }

        .quote-text {
            font-size: 1.1rem;
            font-style: italic;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .author-avatar {
            width: 60px;       
            height: 60px;
            background-color: var(--success-bg);
            border-radius: 50%; 
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
        }

        .author-avatar svg {
            width: 30px;        
            height: 30px;
            fill: currentColor; 
        }

        .author-info h4 {
            margin: 0;
            color: var(--text-main);
            font-size: 1.1rem;
        }

        .author-info span {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .presentation {
            padding: 80px 0;
            text-align: center;
            background: var(--bg-light);
        }

        .presentation h2 {
            margin-bottom: 25px;
            font-size: 2.5rem;
            color: var(--primary-blue);
        }

        .presentation > p {
            max-width: 800px;
            margin: 0 auto 50px;
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .odd-section {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background-color: var(--white);
            border-left: 8px solid var(--primary-red);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(229, 36, 59, 0.08);
            text-align: left;
            margin-top: 30px;
        }

        .odd-content {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .odd-content h4 {
            color: var(--primary-red);
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .odd-content p {
            margin: 0;
            color: var(--text-main);
        }

        .final-cta {
            background: var(--gradient-primary);
            color: var(--white);
            text-align: center;
            padding: 30px 20px;
        }

        .final-cta h2 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .final-cta p {
            font-size: 1rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .hero-content h1 { font-size: 2.8rem; }
            .hero-content h3 { font-size: 1.4rem; }
            .BOUBOU { flex-direction: column; padding: 0 20px; }
            .odd-content { flex-direction: column; text-align: center; }
            .odd-section { border-left: none; border-top: 8px solid var(--primary-red); }
        }

        .main-footer {
            background-color: var(--text-dark); 
            color: var(--white);
            text-align: center;
            padding: 60px 20px 20px;
        }

        .footer-content {
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .footer-content h3 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--primary-green-f);
        }

        .footer-sub {
            color: var(--white);
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="logo-container">
            <a href="index.php?page=home" class="logo-link">
                <img src="./assets/images/LogoMainsTendues.webp" alt="Logo Mains Tendues" class="logo-img" width="105", height="105"/>
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
