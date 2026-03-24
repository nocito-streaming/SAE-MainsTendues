<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


// 1. Configuration et variables
$dirPages = './src/pages/';
$rawPage = $_GET['page'] ?? 'home'; // "home" par défaut selon ta liste

// 2. Nettoyage de sécurité (Regex)
$page = preg_replace('/[^a-zA-Z0-9\-]/', '', $rawPage);

// 3. Gestion spécifique de la sécurité (ex: page Admin)
// C'est ici qu'on décide si l'utilisateur a le droit de voir la page
$isAdmin = false; // Simulation : à remplacer par la logique de connexion plus tard


if ($page === 'admin' && !$isAdmin) {
    // Si pas admin, on le renvoie vers le login ou l'accueil
    header('Location: index.php?page=login');
    exit;
}
// 4. Construction du chemin
$filepath = $dirPages . $page . '.php';

// Structure de chaque page

// Le Header (Menu, CSS, etc.)
if (file_exists('./src/commons/header.php')) {
    include './src/commons/header.php';
}

// Le Corps de la page (Dynamique)
if (file_exists($filepath)) {
    include $filepath;
} else {
    // Si la page n'existe pas, on affiche la home
    include $dirPages . 'home.php';
}
?>
