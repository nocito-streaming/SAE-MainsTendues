<?php
/**
 * Détail d'une demande d'aide (oneHelpOffer).
 * Gère l'affichage dynamique de l'annonce et le traitement du formulaire de réponse via l'API Resend.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;
require_once('./src/db_config.php');
require_once('./src/functions.php');

$idR = $_GET['idR'] ?? null;
$message_success = false;

// Récupération des données de l'annonce
if ($idR) {
    try {
        global $db;
        
        // Jointure pour récupérer l'annonce, la ville associée et les infos du demandeur
        $sql = "SELECT HelpRequests.*, Address.city, defUser.fName, defUser.sName 
                FROM HelpRequests 
                INNER JOIN Address ON HelpRequests.idAdr = Address.idAdr
                INNER JOIN InNeed ON HelpRequests.inNeed_id = InNeed.user_id
                INNER JOIN defUser ON InNeed.user_id = defUser.user_id
                WHERE HelpRequests.idR = :idR";
        
        $stmt = $db->prepare($sql);
        $stmt->execute(['idR' => $idR]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$r) {
            die("Désolé, cette annonce n'existe plus.");
        }

        // Formatage des variables pour la vue
        $req_title = $r['hType'] . " - " . $r['city'];
        if (isset($r['title'])) { 
            $req_title = $r['title']; 
        } 
        
        $req_type = $r['hType'];
        $req_urgency = $r['urgencyLevel'];
        $req_content = $r['content'];
        $req_name = $r['fName'] . " " . $r['sName'];
        $req_city = $r['city'];
        $req_date = date('d/m/Y', strtotime($r['updated_at']));
        $req_status = $r['status']; 

    } catch (PDOException $e) {
        die("Erreur BDD : " . $e->getMessage());
    }
} else {
    die("Aucune annonce sélectionnée.");
}

// Traitement de la proposition d'aide (Envoi d'email via Resend)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstMessage'])) {
    $FirstMessage = $_POST['firstMessage'];
    $email = "snt.thom@gmail.com";
    $req_title = "Nouveau demande d'aide";
    $result = sendEmailFirstMessage($FirstMessage, $email, $req_title);
    if ($result) {
        $message_success = true;
    } else {
        echo "<div style='color:red; background:white; padding:10px;'>";
        echo "<strong>Erreur d'envoi API :</strong><br>";
        echo "Une erreur est survenue lors de l'envoi de votre proposition d'aide. Veuillez réessayer plus tard.";
        echo "</div>";
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/oneHelpOffer.css">

<div class="page-header">
    <div class="header-content">
        <h2>Détails de la demande</h2>
        <p>Découvrez les besoins de <?php echo explode(' ', $req_name)[0]; ?> et proposez votre aide.</p>
    </div>
</div>

<div class="wrap detail-wrap">
    
    <a href="index.php?page=offerHelp" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour aux annonces
    </a>

    <?php if ($message_success): ?>
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> Votre proposition d'aide a bien été envoyée à <?php echo explode(' ', $req_name)[0]; ?> !
        </div>
    <?php endif; ?>

    <div class="detail-grid">
        <div class="detail-main">
            <div class="detail-card">
                <div class="card-header-flex">
                    <div class="badges-group">
                        <span class="badge badge-type"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($req_type); ?></span>
                        <span class="badge <?php echo ($req_urgency === 'Urgent') ? 'badge-urgent' : 'badge-normal'; ?>">
                            <i class="fas <?php echo ($req_urgency === 'Urgent') ? 'fa-exclamation-circle' : 'fa-clock'; ?>"></i> 
                            <?php echo htmlspecialchars($req_urgency); ?>
                        </span>
                    </div>
                    <span class="date-posted">Publié le <?php echo $req_date; ?></span>
                </div>

                <h1 class="detail-title"><?php echo htmlspecialchars($req_title); ?></h1>
                
                <div class="requester-info">
                    <div class="requester-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="requester-text">
                        <strong><?php echo htmlspecialchars($req_name); ?></strong>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($req_city); ?></span>
                    </div>
                </div>

                <hr class="detail-divider">

                <div class="detail-description">
                    <h3><i class="fas fa-align-left"></i> Description du besoin</h3>
                    <p><?php echo nl2br(htmlspecialchars($req_content)); ?></p>
                </div>
            </div>
        </div>

        <div class="detail-sidebar">
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                
                <?php if (!$user_id): ?>
                    <h3>Prêt à aider ?</h3>
                    <p class="action-desc">Vous devez être connecté à votre compte pour répondre à cette demande.</p>
                    <a href="index.php?page=login" class="btn-submit">Se connecter</a>
                    <a href="index.php?page=signUp" class="btn-outline" style="margin-top: 10px; text-align: center; display: block;">Créer un compte</a>
                
                <?php elseif ($req_status !== 'open'): ?>
                    <h3>Demande pourvue</h3>
                    <p class="action-desc">Cette demande d'aide a déjà été acceptée par un autre bénévole ou fermée par l'utilisateur.</p>
                    <button class="btn-submit" disabled style="background: #cbd5e1; cursor: not-allowed; box-shadow: none;">Action indisponible</button>

                <?php else: ?>
                    <h3>Proposer mon aide</h3>
                    <p class="action-desc">Envoyez un petit message à <?php echo explode(' ', $req_name)[0]; ?> pour lui dire comment vous pouvez l'aider.</p>
                    
                    <form method="POST" action="index.php?page=oneHelpOffer&idR=<?php echo $idR; ?>" class="proposal-form">
                        <div class="form-group">
                            <textarea name="firstMessage" rows="5" placeholder="Bonjour, je suis disponible ce samedi pour vous aider avec..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Envoyer ma proposition
                        </button>
                    </form>
                    <p class="security-note"><i class="fas fa-shield-alt"></i> Vos coordonnées ne seront partagées que si votre aide est acceptée.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>