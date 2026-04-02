<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id){
    header("Location: index.php?page=login");
    exit;
}
$information = getAllUserInfo($user_id);

?>

<<<<<<< Updated upstream
<h1>Page de profile</h1>
<div class = "ChangerInfo" >
    <div class = "Adresse" ></div>
=======
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
                    <img src="https://ui-avatars.com/api/?name=Jean+Dupont&background=28B463&color=fff&size=120" alt="Avatar" class="profile-avatar">
                    <button class="edit-avatar-btn" title="Changer la photo"><i class="fas fa-camera"></i></button>
                </div>
                
                <h3 class="profile-name">Jean Dupont</h3>
                <p class="profile-nickname">@Jeannot64</p>
                <div class="profile-badge">
                    <i class="far fa-calendar-alt"></i> Membre depuis Janvier 2024
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
                        <span class="stat-number">3</span>
                        <span class="stat-label">Demandes d'aide</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #3b82f6; background: #eff6ff;"><i class="fas fa-hands-helping"></i></div>
                    <div class="stat-details">
                        <span class="stat-number">5</span>
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
                        <div class="info-value">jean.dupont@email.com</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone"></i> Numéro de téléphone</div>
                        <div class="info-value">06 12 34 56 78</div>
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
                            12B Avenue des Mésanges<br>
                            64600 Anglet
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
>>>>>>> Stashed changes
</div>