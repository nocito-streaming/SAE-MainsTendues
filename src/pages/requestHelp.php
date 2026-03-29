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
                <label for="hType"><i class="fas fa-hands-helping"></i> De quel type d'aide avez-vous besoin ?</label>
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

            <div class="form-row">
                <div class="form-group half">
                    <label for="city"><i class="fas fa-map-marker-alt"></i> Votre ville</label>
                    <input type="text" name="city" id="city" value="Anglet" required>
                </div>

                <div class="form-group half">
                    <label for="urgency"><i class="fas fa-exclamation-circle"></i> Niveau d'urgence</label>
                    <select name="urgency" id="urgency" required>
                        <option value="Normal">Normal (Dans la semaine)</option>
                        <option value="Urgent">Urgent (Dès que possible)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="content"><i class="fas fa-align-left"></i> Description de votre besoin</label>
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