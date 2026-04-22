<?php
require_once  './src/functions.php';
require_once  './src/db_config.php';
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id){
    header("Location: index.php?page=login");
    exit;
}
$user_info_array = getUserInfoToChange($user_id);
$view = $_GET['view'] ?? 'user_info';
switch ($view) {
    case 'user_info': ?>
    <!-- Code pour afficher les informations de l'utilisateur et changer information de l'utilisateur -->
        <p>Email: <?php echo $user_info_array['email']?></p>
        <p>Telephone: <?php echo $user_info_array['tel']?></p>
        <p>Prenom: <?php echo $user_info_array['fName']?></p>
        <p>Nom: <?php echo $user_info_array['sName']?></p>
        <p>Ville: <?php echo $user_info_array['city']?></p>
        <p>Code postal: <?php echo $user_info_array['postalCode']?></p>
        <p>Numero de la rue: <?php echo $user_info_array['street']?></p>
        <p>Numero de maison :<?php echo $user_info_array['homeN']?></p>
        <p>Mot de passe: ************</p>
    <!-- FIN ICI -->
        <?php break;
    case 'settings':?>
    <!-- Code pour afficher les informations de l'utilisateur et changer les differents parametres
     de site(autorisation pour envoyer des mails, etc.) -->
        <div>
            <p>Je veut recevoir des mails</p>
            <select>
                <option>Oui</option>>
                <option>Non</option>
            </select>
        </div>
    <!-- FIN ICI -->
        <?php break;
    default:
        $view = 'user_info';
}
?>

<!--Les "buttons" a l'aide de lesquelles utilisateur choisis la category des parametres-->
<ul class="nav-links">
    <li class="<?php echo $view === 'settings' ? 'active' : ''; ?>">
        <a href="index.php?page=userParam&view=settings"><i class="fa-solid fa-gear"></i>Settings</a>
    </li>
    <li class="<?php echo $view === 'user_info' ? 'active' : ''; ?>">
        <a href="index.php?page=userParam&view=user_info"><i class="fa-solid fa-shield"></i> Informations Confidentiel</a>
    </li>
</ul>
<!-- FIN ICI -->