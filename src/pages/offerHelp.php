<?php
require_once ('./src/db_config.php');


$filterType = $_GET['hType'] ?? '';
$filterCity = $_GET['city'] ?? '';
$cityParam = $filterCity ? "%$filterCity%" : '';
$typeParam = $filterType ? : '';
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
<link rel="stylesheet" href="./assets/css/offerHelp.css">

<div class="page-header">
    <div class="header-content">
        <h2>Trouvez une mission de solidarité</h2>
        <p>Découvrez les demandes d'aide près de chez vous et tendez la main à ceux qui en ont besoin.</p>
    </div>
</div>

<div class="wrap">
    <div class="filter-section">
        <form method="GET" action="index.php" class="filter-form">
            <input type="hidden" name="page" value="offerHelp">
            
            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="city" placeholder="Ville ou Code Postal..." value="<?php echo htmlspecialchars($filterCity); ?>">
            </div>
            
            <div class="input-group">
                <i class="fas fa-hands-helping"></i>
                <select name="hType">
                    <option value="">Tous les types d'aide</option>
                    <option value="Courses" <?php if($filterType === 'Courses') echo 'selected'; ?>>Courses</option>
                    <option value="Compagnie" <?php if($filterType === 'Compagnie') echo 'selected'; ?>>Compagnie</option>
                    <option value="Bricolage" <?php if($filterType === 'Bricolage') echo 'selected'; ?>>Bricolage</option>
                    <option value="Jardinage" <?php if($filterType === 'Jardinage') echo 'selected'; ?>>Jardinage</option>
                    <option value="Informatique" <?php if($filterType === 'Informatique') echo 'selected'; ?>>Informatique</option>
                </select>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <select name="hType">
                    <option value="">Niveau d'urgence</option>
                    <option value="Faible" <?php if($filterType === 'Faible') echo 'selected'; ?>>Faible</option>
                    <option value="Moyen" <?php if($filterType === 'Moyen') echo 'selected'; ?>>Moyen</option>
                    <option value="Élevé" <?php if($filterType === 'Élevé') echo 'selected'; ?>>Élevé</option>
                </select>
            </div>

            
            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Rechercher</button>
            <a href="index.php?page=offerHelp" class="btn-reset" title="Réinitialiser"><i class="fas fa-times"></i></a>
        </form>
    </div>

    <div class="grid-container">
        <?php if (count($requests) > 0){ ?>
            <?php foreach ($requests as $r){ ?>
                <a href="index.php?page=oneHelpOffer" class="request-card">
                    <div class="card-header">
                        <div class="badges-group">
                            <span class="badge badge-blue"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($r['hType']); ?></span>
                            <span class="badge badge-green"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($r['city']); ?></span>
                            <!-- ADD htmlspecialchars() AFTER cleaning the data base -->
                            <span class="badge badge-red"><i class="fa-solid fa-triangle-exclamation"></i>  
                            <?php echo $r['urgencyLevel']; ?></span>
                        </div>
                    </div>

                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($r['title'] ?? $r['hType']); ?></h3>
                        <p class="card-desc">"<?php echo htmlspecialchars($r['content']); ?>"</p>
                    </div>

                    <hr class="card-divider">

                    <div class="card-footer">
                        <span class="card-timestamp"><i class="far fa-clock"></i> Publié le <?php echo htmlspecialchars(date('d/m/Y', strtotime($r['updated_at']))); ?></span>
                        <span class="card-btn-mock">Proposer mon aide <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            <?php } ?>
        <?php } else { ?>
            <div class="no-results">
                <i class="fas fa-search-minus fa-3x"></i>
                <p>Aucune demande ne correspond à vos critères.</p>
            </div>
        <?php } ?>
    </div>
</div>
