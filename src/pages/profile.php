<?php
require_once './src/functions.php';
require_once './src/auth.php';
require_once './src/db_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id){
    header("Location: index.php?page=login");
    exit;
}
$information = getAllUserInfo($user_id);
$formatedDate = dateformatter($information[0]['created_at']);
$nbRequests = getNumberHelpRequestsByUser($user_id);
$nbHelp = getNumberCompletedRequestsByUser($user_id);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="./assets/css/profile.css">

<div class="page-header">
    <div class="header-content">
        <h2>Mon Profil</h2>
        <p>Gérez vos informations personnelles et consultez votre activité.</p>
    </div>
</div>
<div class="wrap profile-wrap">
    <div class="profile-grid">
        
        <div class="profile-sidebar">
            <div class="profile-card text-center">
                <div class="avatar-container">
                    <?php if ($information[0]['photo'] !== NULL){ ?>
                        <img src="<?php echo htmlspecialchars($information[0]['photo']); ?>" alt="Avatar" class="profile-avatar">
                    <?php } else { ?>
                        <img src="https://ui-avatars.com/api/?name=Jean+Dupont&background=28B463&color=fff&size=120" alt="Avatar" class="profile-avatar">
                    <?php }?>
                    <button class="edit-avatar-btn" title="Changer la photo"><i class="fas fa-camera"></i></button>
                </div>
                
                <h3 class="profile-name"><?php echo $information[0]['fName'] . " " . $information[0]['sName']; ?></h3>
                <p class="profile-nickname"><?php echo $information[0]['nickname']?></p>
                <div class="profile-badge">
                    <i class="far fa-calendar-alt"></i><?php echo"Membre depuis " . $formatedDate; ?>
                </div>
                
                <hr class="profile-divider">
                
                <a href="index.php?page=editProfile" class="btn-profile btn-outline">
                    <i class="fas fa-pen"></i> Modifier le profil
                </a>
                <a href="index.php?page=logout" class="btn-profile btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </a>
            </div>
        </div>

        <div class="profile-content">
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-hand-holding-heart"></i></div>
                    <div class="stat-details">
                        <span class="stat-number"><?php echo $nbRequests?></span>
                        <span class="stat-label">Demandes d'aide</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #3b82f6; background: #eff6ff;"><i class="fas fa-hands-helping"></i></div>
                    <div class="stat-details">
                        <span class="stat-number"><?php echo $nbHelp?></span>
                        <span class="stat-label">Aides proposées</span>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <div class="card-header-profile">
                    <h4>Informations personnelles</h4>
                </div>
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-envelope"></i> Adresse e-mail</div>
                        <div class="info-value"><?php echo $information[0]['email']?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone"></i> Numéro de téléphone</div>
                        <div class="info-value"><?php echo $information[0]['tel']?></div>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <div class="card-header-profile">
                    <h4> Lieu de résidence</h4>
                </div>
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-home"></i> Domicile</div>
                        <div class="info-value">
                            <?php if($information[0]['idAdr'] !== 1){
                                echo $information[0]['homeN'] . " " . $information[0]['street']?><br>
                                <?php echo $information[0]['postalCode'] . " ". $information[0]['city'];
                            } else {?>
                            <p>Vous n'avez pas encore saisis address</p>
                            <button class = "SCKMYCKC"> Ajouter maintenant!</button>>
                            <?php }?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>