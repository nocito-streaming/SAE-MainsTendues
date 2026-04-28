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

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['icon_url'])) {
    $image_url = $_POST['icon_url'];
    global $db;
    try {
        $stmt = $db->prepare("UPDATE User SET photo = :url WHERE user_id = :user_id");
        $stmt->execute([
            'url' => $image_url,
            'user_id' => $user_id
        ]);
        $message = "<div class='alert-success' style='color: #2d6a4f; background: #dcfce7; padding: 10px; border-radius: 5px; margin-bottom: 15px;'><i class='fas fa-check-circle'></i> Photo de profil mise à jour !</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert-error' style='color: #e5243b; background: #fee2e2; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Erreur : " . $e->getMessage() . "</div>";
    }
}

$information = getAllUserInfo($user_id);
$formatedDate = dateformatter($information[0]['created_at']);
$nbRequests = getNumberHelpRequestsByUser($user_id);
$nbHelp = getNumberCompletedRequestsByUser($user_id);
$adr_info = getUserAddress($user_id);
?>

<script>
    UPLOADCARE_PUBLIC_KEY = '93c13b761ea513e8eb80';
    UPLOADCARE_LOCALE = 'fr';
</script>
<script src="https://ucarecdn.com/libs/widget/3.x/uploadcare.full.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="./assets/css/profile.css">

<style>
    .uploadcare--widget {
        display: none !important; 
    }
</style>

<div class="page-header">
    <div class="header-content">
        <h2>Mon Profil</h2>
        <p>Gérez vos informations personnelles et consultez votre activité.</p>
    </div>
</div>

<div class="wrap profile-wrap">
    
    <?php echo $message; ?>

    <div class="profile-grid">
        <div class="profile-sidebar">
            <div class="profile-card text-center">
                
                <div class="avatar-container">
                    <?php if (!empty($information[0]['photo'])){ ?>
                        <img src="<?php echo htmlspecialchars($information[0]['photo']); ?>" alt="Avatar" class="profile-avatar">
                    <?php } else { ?>
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($information[0]['fName'] . '+' . $information[0]['sName']); ?>&background=28B463&color=fff&size=120" alt="Avatar" class="profile-avatar">
                    <?php }?>
                    
                    <button type="button" class="edit-avatar-btn" id="custom-upload-btn" title="Changer la photo">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>

                <form id="avatar-form" action="" method="POST" style="display:none;">
                    <input type="hidden" role="uploadcare-uploader" name="icon_url" id="uc-input" data-crop="1:1" data-images-only="true">
                </form>
                
                <h3 class="profile-name"><?php echo htmlspecialchars($information[0]['fName'] . " " . $information[0]['sName']); ?></h3>
                <p class="profile-nickname"><?php echo htmlspecialchars($information[0]['nickname'] ?? ''); ?></p>
                <div class="profile-badge">
                    <i class="far fa-calendar-alt"></i> <?php echo "Membre depuis " . $formatedDate; ?>
                </div>
                
                <hr class="profile-divider">
                
                <a href="index.php?page=editProfile" class="btn-profile btn-outline">
                    <i class="fas fa-pen"></i> Modifier le profil
                </a>
                <a href="index.php?page=logout" class="btn-profile btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </a>
                <a href="index.php?page=userParam&view=delete_account" class="btn-profile btn-danger" style="margin-top: 10px; background-color: #dc3545; color: white;">  
                    <i class="fas fa-trash-alt"></i> Supprimer le compte
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
                        <div class="info-labels ">
                            <div class="info-label"><i class="fas fa-envelope"></i> Adresse e-mail</div>
                            <div class="info-value"><?php echo htmlspecialchars($information[0]['email']); ?></div>
                        </div>
                        <a href="index.php?page=userParam&view=user_info" class="btn-change" title="Modifier"><i class="fas fa-pen"></i></a>
                    </div>
                    <div class="info-item">
                        <div class="info-labels">
                            <div class="info-label"><i class="fas fa-phone"></i> Numéro de téléphone</div>
                            <div class="info-value" id="tel-text"><?php echo htmlspecialchars($information[0]['tel'] ?? ''); ?></div>
                        </div>
                        <a class="btn-change" id="edit-btn" title="Modifier" href="index.php?page=userParam&view=user_info">
                            <i class="fas fa-pen"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                    <div class="card-header-profile">
                        <h4> Lieu de résidence</h4>
                    </div>
                    <?php if($adr_info['idAdr'] == 1){ ?>
                    <p>Vous n'avez pas encore saisi d'adresse.</p>
                    <a class="add-address" href="index.php?page=userParam&view=user_info"> Ajouter maintenant !</a>
                     <?php } else {?>
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-home"></i> Domicile</div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($adr_info['homeN'] . " " . $adr_info['street']); ?><br>
                                <?php echo htmlspecialchars($adr_info['postalCode'] . " ". $adr_info['city']); ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const widget = uploadcare.Widget('#uc-input');
    document.getElementById('custom-upload-btn').addEventListener('click', function() {
        widget.openDialog();
    });
    
    widget.onUploadComplete(function(info) {
        document.getElementById('avatar-form').submit();
    });
});
</script>