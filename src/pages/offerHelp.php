<?php
$filterCity = $_GET['city'] ?? '';
$filterType = $_GET['hType'] ?? '';

$requests = [
    [
        'hType' => 'Courses',
        'status' => 'En attente',
        'updated_at' => '2026-03-29 10:30:00',
        'city' => 'Anglet',
        'content' => 'Bonjour, j\'aurais besoin d\'aide pour porter mes courses ce jeudi.'
    ],
    [
        'hType' => 'Bricolage',
        'status' => 'Urgent',
        'updated_at' => '2026-03-28 15:45:00',
        'city' => 'Biarritz',
        'content' => 'Mon évier fuit, j\'aurais besoin de quelqu\'un qui s\'y connait un peu en plomberie.'
    ],
    [
        'hType' => 'Compagnie',
        'status' => 'En attente',
        'updated_at' => '2026-03-27 09:15:00',
        'city' => 'Bayonne',
        'content' => 'Je cherche une personne pour discuter et faire une promenade d\'une heure dans le parc.'
    ]
];
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
            <a href="index.php?page=offerHelp" class="btn-reset"><i class="fas fa-times"></i></a>
        </form>
    </div>

    <div class="grid-container">
        <?php if (count($requests) > 0): ?>
            <?php foreach ($requests as $r): ?>
                <a href="#" class="request-card">
                    <div class="card-header">
                        <span class="badge badge-blue"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($r['hType']); ?></span>
                        <span class="badge badge-red"><?php echo htmlspecialchars($r['status']); ?></span>
                    </div>

                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($r['hType']); ?> à <?php echo htmlspecialchars($r['city']); ?></h3>
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