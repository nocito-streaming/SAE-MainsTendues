<?php
require_once ('./src/db_config.php');

// Récupération sécurisée des filtres
$filterType = $_GET['hType'] ?? '';
$filterCity = trim($_GET['city'] ?? '');

try {
    global $db;
    
    $sql = "SELECT HelpRequests.*, Address.city 
            FROM HelpRequests 
            INNER JOIN Address ON HelpRequests.idADr = Address.idAdr 
            WHERE 1=1";
            
    $params = [];

    if ($filterCity !== '') {
        $sql .= " AND Address.city LIKE :city";
        $params['city'] = "%$filterCity%";
    }

    if ($filterType !== '') {
        $sql .= " AND HelpRequests.hType = :hType";
        $params['hType'] = $filterType;
    }

    $sql .= " ORDER BY updated_at DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
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
            
            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Rechercher</button>
            <a href="index.php?page=offerHelp" class="btn-reset" title="Réinitialiser"><i class="fas fa-times"></i></a>
        </form>
    </div>

    <div class="grid-container">
        <?php if (count($requests) > 0): ?>
            <?php foreach ($requests as $r): ?>
                <a href="#" class="request-card">
                    <div class="card-header">
                        <div class="badges-group">
                            <span class="badge badge-blue"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($r['hType']); ?></span>
                            <span class="badge badge-green"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($r['city']); ?></span>
                            <span class="badge badge-red"><?php echo htmlspecialchars($r['status']); ?></span>
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
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search-minus fa-3x"></i>
                <p>Aucune demande ne correspond à vos critères.</p>
            </div>
        <?php endif; ?>
    </div>
</div>