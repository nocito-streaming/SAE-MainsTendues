<?php
require_once  './src/functions.php';
require_once  './src/db_config.php';
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: index.php?page=login");
    exit;
}
$user_info_array = getUserInfoToChange($user_id);
$view = $_GET['view'] ?? 'user_info';
if (!in_array($view, ['user_info', 'settings'])) {
    $view = 'user_info';
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="./assets/css/userParam.css">

<div class="page-header">
    <div class="header-content">
        <h2>Paramètres du compte</h2>
        <p>Vos données ne seront modifiées que si vous cliquez sur "Valider"</p>
    </div>
</div>

<div class="wrap">
    <ul class="param-tabs">
        <li class="<?php echo $view === 'user_info' ? 'active' : ''; ?>">
            <a href="index.php?page=userParam&view=user_info">
                <i class="fas fa-user-edit"></i> Informations personnelles
            </a>
        </li>
        <li class="<?php echo $view === 'settings' ? 'active' : ''; ?>">
            <a href="index.php?page=userParam&view=settings">
                <i class="fas fa-cog"></i> Préférences
            </a>
        </li>
    </ul>

    <div class="form-container">

        <?php if ($view === 'user_info'): ?>
        <form method="POST" action="index.php?page=userParam&view=user_info" class="help-form">

            <div class="section-header">
                <h4><i class="fas fa-user"></i> Identité</h4>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label for="fName"><i class="fas fa-user"></i> Prénom</label>
                    <input type="text" name="fName" id="fName" value="<?php echo htmlspecialchars($user_info_array['fName'] ?? ''); ?>">
                </div>
                <div class="form-group half">
                    <label for="sName"><i class="fas fa-user"></i> Nom</label>
                    <input type="text" name="sName" id="sName" value="<?php echo htmlspecialchars($user_info_array['sName'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_info_array['email'] ?? ''); ?>">
                </div>
                <div class="form-group half">
                    <label for="tel"><i class="fas fa-phone"></i> Téléphone</label>
                    <input type="text" name="tel" id="tel" value="<?php echo htmlspecialchars($user_info_array['tel'] ?? ''); ?>">
                </div>
            </div>

            <div class="section-header">
                <h4><i class="fas fa-map-marker-alt"></i> Adresse</h4>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label for="homeN"><i class="fas fa-home"></i> N° de maison</label>
                    <input type="text" name="homeN" id="homeN" value="<?php echo htmlspecialchars($user_info_array['homeN'] ?? ''); ?>">
                </div>
                <div class="form-group half">
                    <label for="street"><i class="fas fa-road"></i> Rue</label>
                    <input type="text" name="street" id="street" value="<?php echo htmlspecialchars($user_info_array['street'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label for="postalCode"><i class="fas fa-map-pin"></i> Code postal</label>
                    <input type="text" name="postalCode" id="postalCode" value="<?php echo htmlspecialchars($user_info_array['postalCode'] ?? ''); ?>">
                </div>
                <div class="form-group half">
                    <label for="city"><i class="fas fa-city"></i> Ville</label>
                    <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($user_info_array['city'] ?? ''); ?>">
                </div>
            </div>

            <div class="section-header">
                <h4><i class="fas fa-lock"></i> Sécurité</h4>
            </div>
            <div class="form-group">
                <label for="mdp"><i class="fas fa-key"></i> Nouveau mot de passe</label>
                <input type="password" name="mdp" id="mdp" placeholder="Laisser vide pour ne pas modifier">
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Valider les modifications
            </button>
        </form>

        <?php elseif ($view === 'settings'): ?>
        <form method="POST" action="index.php?page=userParam&view=settings" class="help-form">

            <div class="section-header">
                <h4><i class="fas fa-bell"></i> Notifications</h4>
            </div>
            <div class="form-group">
                <label for="receive_mails"><i class="fas fa-envelope"></i> Recevoir des notifications par mail</label>
                <select name="receive_mails" id="receive_mails">
                    <option value="1">Oui, je souhaite recevoir des mails</option>
                    <option value="0">Non, je ne souhaite pas recevoir de mails</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Enregistrer les préférences
            </button>
        </form>
        <?php endif; ?>

    </div>
</div>
