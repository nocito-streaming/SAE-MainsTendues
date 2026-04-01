<?php
require_once ('./src/db_config.php');
$filterType = $_GET['hType'] ?? '';
$filterCity = $_GET['city'] ?? '';
$cityParam = $filterCity ? "%$filterCity%" : '';
$typeParam = $filterType ? "%$filterType%" : '';
try{
    global $db;
    $sql = "SELECT HelpRequests.*, Address.city 
            FROM HelpRequests 
            INNER JOIN Address ON HelpRequests.idADr = Address.idAdr 
            ORDER BY 
                (CASE 
                    WHEN (Address.city LIKE :city AND :city_raw != '') 
                     AND (HelpRequests.hType = :hType AND :hType_raw != '') THEN 2
                    WHEN (Address.city LIKE :city AND :city_raw != '') 
                      OR (HelpRequests.hType = :hType AND :hType_raw != '') THEN 1
                    ELSE 0 
                END) DESC, 
                updated_at DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        'city' => $cityParam,
        'city_raw' => $filterCity,
        'hType' => $typeParam,
        'hType_raw' => $filterType
    ]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    echo "Error: " . $e -> getMessage();
     $requests = [];
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
        
        <form action="index.php?page=requestHelp" method="POST" class="help-form">
            
            <div class="form-group">
                <label for="title"> Titre de votre demande</label>
                <input type="text" name="title" id="title" maxlength="40" placeholder="Ex : Besoin d'aide pour mes courses" required>
            </div>

            <div class="form-group">
                <label for="hType"> De quel type d'aide avez-vous besoin ?</label>
                <select name="hType" id="hType" required onchange="toggleOtherInput(this)">
                    <option value="" disabled selected>Choisissez une catégorie...</option>
                    <option value="Courses">Courses</option>
                    <option value="Compagnie">Compagnie</option>
                    <option value="Bricolage">Bricolage</option>
                    <option value="Jardinage">Jardinage</option>
                    <option value="Informatique">Informatique</option>
                    <option value="Autre">Autre (Précisez)</option>
                </select>
                
                <input type="text" name="hType_other" id="hType_other" placeholder="Quel est ce type d'aide ?" style="display: none; margin-top: 10px;">
            </div>

            <div class="address-section" style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef;">
                <h3 style="margin-top: 0; margin-bottom: 15px; color: #2c3e50; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 8px;">
                    Lieu de l'intervention
                </h3>

                <div class="form-row">
                    <div class="form-group" style="flex: 1; padding-right: 10px;">
                        <label for="homeN">N° rue / Bât.</label>
                        <input type="text" name="homeN" id="homeN" placeholder="Ex: 12B">
                    </div>
                    
                    <div class="form-group" style="flex: 3;">
                        <label for="street">Nom de la rue</label>
                        <input type="text" name="street" id="street" placeholder="Ex: Avenue des Mésanges" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half" style="padding-right: 10px;">
                        <label for="postalCode">Code postal</label>
                        <input type="text" name="postalCode" id="postalCode" placeholder="Ex: 64600" required>
                    </div>

                    <div class="form-group half">
                        <label for="city">Ville</label>
                        <input type="text" name="city" id="city" value="Anglet" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="content"> Description de votre besoin</label>
                <textarea name="content" id="content" rows="5" placeholder="Expliquez brièvement ce dont vous avez besoin. Par exemple : 'J'aurais besoin d'aide pour tondre ma pelouse ce week-end...'" required></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Publier ma demande
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
</script>