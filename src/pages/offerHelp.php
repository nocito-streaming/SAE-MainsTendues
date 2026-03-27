<?php
require_once ('./src/db_config.php');
try{
    $requests = query('SELECT * FROM HelpRequests 
                    INNER JOIN Address ON HelpRequests.idADr = Address.idAdr 
                    INNER JOIN InNeed ON InNeed.user_id = HelpRequests.inNeed_id 
                    ORDER BY updated_at DESC')
        -> fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo 'Error :' . $e->getMessage();
    $requests = [];
}

?>
<link rel="stylesheet" href="./assets/css/offerHelp.css">
<div class = "wrap">
<div class = 'grid-container'>
    <?php if (count($requests) > 0){
         foreach ($requests as $r){
             ?>
            <a href="index.php?page=home" class="request-card">
                <div class="card-header">
                    <span class="badge badge-blue"><?php echo $r['hType']; ?></span>
                    <span class="badge badge-red"><?php echo $r["status"]; ?></span>
                </div>

                <div class="card-timestamp">
                    <?php echo $r['updated_at']; ?>
                </div>

                <hr class="card-divider">

                <div class="card-body">
                    <p><strong>Localisation :</strong> <?php echo $r['city']; ?></p>
                    <p><strong>Description :</strong> <?php echo $r['content']; ?></p>
                </div>

                <span class="card-btn-mock">
                    Je peux aider cette personne
                </span>
            </a>
         <?php }
    } ?>
</div>