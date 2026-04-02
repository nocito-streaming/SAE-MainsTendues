<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../functions.php';
global $db;
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/requestHelp.css">

    <div class="page-header header-error">
        <div class="header-content">
            <h2>Accès Restreint</h2>
            <p>Rejoignez notre communauté pour publier une demande.</p>
        </div>
    </div>

    <div class="wrap-error">
        <div class="form-container-error auth-container">
            <div class="form-icon icon-error">
                <i class="fas fa-lock"></i>
            </div>
            
            <h3>Authentification requise</h3>
            <p class="auth-desc">Pour faire une demande d'aide, il faut d'abord vous connecter à votre compte !</p>
            
            <div class="auth-buttons">
                <a href="index.php?page=login" class="btn-submit auth-btn">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </a>
                <a href="index.php?page=signUp" class="btn-submit auth-btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Créer un compte
                </a>
            </div>
        </div>
    </div>
    <?php
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hType = trim($_POST["hType"] ?? "");
    $hType_other = trim($_POST["hType_other"] ?? "");
    $urgency = trim($_POST["urgency"] ?? "");
    $content = trim($_POST["content"] ?? "");
    
    $result = addHelpRequest($hType, $content, $urgency, $user_id, 1);
    
    if ($result === true) {
        echo "<div class='success-banner'>La Demande d'aide a été ajoutée avec succès.</div>";
        exit;
    } else {
        $error = $result;
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="./assets/css/requestHelp.css">

<div class="page-header">
    <div class="header-content">
        <h2>Demander de l'aide</h2>
        <p>Remplissez ce formulaire simplement. Nos bénévoles sont là pour vous accompagner au quotidien.</p>
    </div>
</div>

<div class="wrap">
    <div class="form-container">
        <div class="form-icon">
            <i class="fas fa-hand-holding-heart"></i>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error-banner">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=requestHelp" method="POST" class="help-form">

            <div class="form-group">
                <label for="hType">De quel type d'aide avez-vous besoin ?</label>
                <select name="hType" id="hType" required onchange="toggleOtherInput(this)">
                    <option value="" disabled selected>Choisissez une catégorie...</option>
                    <option value="Courses">Courses</option>
                    <option value="Compagnie">Compagnie</option>
                    <option value="Bricolage">Bricolage</option>
                    <option value="Jardinage">Jardinage</option>
                    <option value="Informatique">Informatique</option>
                    <option value="Autre">Autre (Précisez)</option>
                </select>
                
                <input type="text" name="hType_other" id="hType_other" class="hidden-input" placeholder="Quel est ce type d'aide ?">
            </div>
            <div class="address-section" style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef;">
                <h3 style="margin-top: 0; margin-bottom: 15px; color: #2c3e50; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 8px;">
                    Lieu de l'intervention
                </h3>

            <div class="form-row">
                <div class="form-group half">
                    <label for="city"> Votre ville</label>
                    <input type="text" name="city" id="city" value="Anglet" required>
                </div>

                <div class="form-group half">
                    <label for="urgency">Niveau d'urgence</label>
                    <select name="urgency" id="urgency" required>
                        <option value="Normal">Normal (Dans la semaine)</option>
                        <option value="Urgent">Urgent (Dès que possible)</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="content"> Description de votre besoin</label>
                <textarea name="content" id="content" rows="5" placeholder="Expliquez brièvement ce dont vous avez besoin. Par exemple : 'J'aurais besoin d'aide pour tondre ma pelouse ce week-end...'" required></textarea>
            </div>
            <button type="submit" class="btn-submit">
                Publier ma demande
            </button>
        </form>
    </div>
</div>

<script>
function toggleOtherInput(selectElement) {
    const otherInput = document.getElementById('hType_other');
    
    if (selectElement.value === 'Autre') {
        otherInput.style.display = 'block';
        otherInput.setAttribute('required', 'required');
    } else {
        otherInput.style.display = 'none';
        otherInput.removeAttribute('required');
        otherInput.value = ''; 
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const hTypeSelect = document.getElementById('hType');
    toggleOtherInput(hTypeSelect);
});
</script>
