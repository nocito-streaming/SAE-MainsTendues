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
$adr_info = getUserAddress($user_id);



$message = "";
$image_url = $_POST['icon_url'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['icon_url'])) {
    global $db;
    try {
        $stmt = $db->prepare("UPDATE User SET User.photo = :url WHERE user_id = :user_id");
        $stmt->execute(['url' => $image_url,
                'user_id' => $user_id
        ]);

        $message = "<p class='success'>Success! Icon saved to database.</p>";
    } catch (PDOException $e) {
        $message = "<p class='error'>Database Error: " . $e->getMessage() . "</p>";
    }
}
?>

<div>
        <title>MainsTendues - Update Icon</title>
        <script>
            UPLOADCARE_PUBLIC_KEY = '93c13b761ea513e8eb80';
            UPLOADCARE_TABS = 'file url camera';
            UPLOADCARE_LOCALE = 'en';
        </script>
        <script src="https://ucarecdn.com/libs/widget/3.x/uploadcare.full.min.js"></script>
        <style>
            body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; display: flex; justify-content: center; }
            .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; width: 400px; }
            .success { color: #2d6a4f; background: #dcfce7; padding: 10px; border-radius: 5px; }
            .error { color: #e5243b; background: #fee2e2; padding: 10px; border-radius: 5px; }
            .save-btn {
                margin-top: 20px;
                background: #2E86C1;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                width: 100%;
            }
            .save-btn:hover { background: #21618C; }
            .uploadcare--widget__button_type_open {
                background-color: #2D6A4F !important;
            }
        </style>
    </head>
    <div>
    <div class="card">
        <h2>Update Profile Icon</h2>
        <p style="color: #64748b; margin-bottom: 20px;">Upload a photo, then click Save.</p>
        <?php  echo $message; ?>
        <form action="" method="POST">
            <input type="hidden"
                   role="uploadcare-uploader"
                   name="icon_url"
                   data-crop="1:1"
                   data-images-only="true">
            <br>
            <button type="submit" class="save-btn">Save to Profile</button>
        </form>
    </div>
</div>










    

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
                <a href="index.php?page=userParam&view=delete_account" class="btn-profile btn-danger">  
                    <?php
                    $userId = $_SESSION['user_id'];
                    deleteUser($_SESSION['user_id']);
                    ?>
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
                            <div class="info-value"><?php echo $information[0]['email']?></div>
                        </div>
                        <a href = "index.php?page=userParam&view=user_info" class="btn-change" title="Supprimer"><i class="fas fa-pen"></i></a>
                    </div>
                    <div class="info-item">
                        <div class="info-labels">
                            <div class="info-label"><i class="fas fa-phone"></i> Numéro de téléphone</div>
                            <div class="info-value" id="tel-text"><?php echo $information[0]['tel']?></div>
                        </div>
                        <a class="btn-change" id="edit-btn" title="Modifier" href = "index.php?page=userParam&view=user_info">
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
                    <p>Vous n'avez pas encore saisis address</p>
                    <a class = "add-address" href = "index.php?page=userParam&view=user_info"> Ajouter maintenant!</a>
                     <?php } else {?>
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-home"></i> Domicile</div>
                            <div class="info-value">
                                <?php echo $adr_info['homeN'] . " " . $adr_info['street']?><br>
                                <?php echo $adr_info['postalCode'] . " ". $adr_info['city'];
                    }?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>