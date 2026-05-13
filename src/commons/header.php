<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MainsTendues est la plateforme gratuite d'entraide locale. Demandez de l'aide pour vos courses et petits travaux ou devenez bénévole pour aider vos voisins.">
    <link rel="icon" type="image/webp" href="/assets/images/LogoMainsTendues.webp">
    <title>Plateforme d'Entraide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>

        :root {
            /* COULEURS PRINCIPALES */
            --primary-green: #2D6A4F;
            --dark-green: #1D8348;    
            --primary-blue: #2E86C1;
            --primary-red: #E5243B;   
            --white: #ffffff;

            /* TEXTES & FONDS */
            --text-dark: #0f172a;       /* Titres forts ET fond de la barre latérale Admin */
            --text-main: #1e293b;       /* Texte standard de lecture */
            --text-muted: #64748b;      /* Descriptions, sous-titres, dates, icônes */
            
            --bg-body: #f1f5f9;         /* Unifie le fond des pages publiques et Admin */
            --bg-light: #f8fafc;        /* Fond des cartes, inputs et entêtes tableaux */
            --bg-dark-hover: #334155;   /* Survol dans la sidebar ET bordures sombres */

            --border-color: #e2e8f0;    /* Unifie toutes les bordures claires (inputs, tableaux) */

            /* Succès */
            --success-bg: #dcfce7;
            --success-text: #16a34a;    /* bordures de succès */
            
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
            font-family: 'Poppins', sans-serif;
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
                <a href="index.php?page=login" class="btn-signup">Se connecter</a>
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
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="index.php?page=inbox" title="Mes messages" style="position: fixed; bottom: 30px; right: 30px; background-color: #28B463; width: 65px; height: 65px; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; z-index: 9999; transition: transform 0.2s ease, background-color 0.2s ease;" onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#1D8348';" onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#28B463';">
            
            <svg style="width: 35px; height: 35px; fill: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                <path d="M284.5 224.8c-6.7 0-13.3 2.1-18.9 5.8s-9.9 9.1-12.5 15.4-3.2 13.1-1.9 19.7 4.6 12.7 9.4 17.4 10.9 8 17.5 9.3 13.5 .6 19.7-2 11.5-7 15.3-12.6 5.7-12.2 5.7-18.9c0-9.1-3.7-17.8-10.1-24.2s-15.1-10-24.2-9.9zm-110.4 0c-6.7 0-13.3 2.1-18.9 5.8s-9.9 9.1-12.5 15.4-3.2 13.1-1.9 19.7 4.6 12.7 9.4 17.4 10.9 8 17.5 9.3 13.5 .6 19.7-2 11.5-7 15.3-12.6 5.7-12.2 5.7-18.9c0-9.1-3.7-17.8-10.1-24.2s-15.1-10-24.2-10l0 0zm220.9 0a34.1 34.1 0 1 0 .4 68.2 34.1 34.1 0 1 0 -.4-68.2zm153.8-55.3c-15.5-24.2-37.3-45.6-64.7-63.6-52.9-34.8-122.4-54-195.7-54-24.2 0-48.3 2.1-72 6.4-14.9-14.3-31.5-26.6-49.5-36.6-66.8-33.3-125.6-20.9-155.3-10.2-2.3 .8-4.3 2.1-5.9 3.9s-2.7 3.9-3.3 6.2-.5 4.7 .1 7.1 1.8 4.4 3.5 6.1C27 56.5 61.6 99.3 53.1 138.3 20 172.2 2 213 2 255.6 2 299 20 339.8 53.1 373.7 61.6 412.7 27 455.6 6 477.2 4.3 479 3.2 481.1 2.5 483.4s-.7 4.7-.1 7 1.7 4.5 3.3 6.2 3.6 3.1 5.9 3.9c29.7 10.7 88.5 23.1 155.3-10.2 18-10 34.7-22.3 49.5-36.6 23.8 4.3 47.9 6.4 72 6.4 73.3 0 142.8-19.2 195.7-54 27.4-18 49.1-39.4 64.7-63.6 17.3-26.9 26.1-55.9 26.1-86.1 0-31-8.8-60-26.1-86.9l0 0zM285.4 409.9c-30.2 .1-60.3-3.8-89.4-11.5l-20.1 19.4c-11.2 10.7-23.6 20-37.1 27.6-16.4 8.2-34.2 13.3-52.5 14.9 1-1.8 1.9-3.6 2.8-5.4 20.2-37.1 25.6-70.5 16.3-100.1-33-26-52.8-59.2-52.8-95.4 0-83.1 104.3-150.5 232.8-150.5s232.9 67.4 232.9 150.5c0 83.1-104.3 150.5-232.9 150.5z"/>
            </svg>

        </a>
    <?php endif; ?>
</body>
</html>
