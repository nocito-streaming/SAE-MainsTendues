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

if ($idR) {
    try {
        global $db;

        $sql = "SELECT HelpRequests.*, Address.city, defUser.fName, defUser.sName, User.email 
                FROM HelpRequests 
                INNER JOIN Address ON HelpRequests.idAdr = Address.idAdr
                INNER JOIN InNeed ON HelpRequests.inNeed_id = InNeed.user_id
                INNER JOIN defUser ON InNeed.user_id = defUser.user_id
                INNER JOIN User ON defUser.user_id = User.user_id
                WHERE HelpRequests.idR = :idR";
        
        $stmt = $db->prepare($sql);
        $stmt->execute(['idR' => $idR]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$r) {
            die("Désolé, cette annonce n'existe plus.");
        }
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstMessage'])) {
    $FirstMessage = $_POST['firstMessage'];
    $email = $r['email']; 
    
    $req_firstName = explode(' ', $req_name)[0]; 
    $checkpoint = CheckExistanceHelp($user_id, $idR);
    $result = false;
    $InsertHelpAct = false;

    if ($checkpoint === false) {
        $result = true;
    }

    if ($result === true) {
        $InsertHelpAct = InsertHelpOffer($FirstMessage, $user_id, $idR);
        $result = sendEmailFirstMessage($FirstMessage, $email, $req_title, $req_firstName);
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="refresh" content="4;url=index.php?page=offerHelp">
            
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                .success-page {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    min-height: 50vh;
                    text-align: center;
                    padding: 40px 20px;
                }
                .success-icon-large {
                    font-size: 5rem;
                    color: #3498db;
                    margin-bottom: 20px;
                    animation: popIn 0.5s ease-out;
                }
                .redirect-text {
                    color: #7f8c8d;
                    margin-top: 20px;
                    font-size: 1.1rem;
                }
                @keyframes popIn {
                    0% { transform: scale(0); opacity: 0; }
                    80% { transform: scale(1.1); }
                    100% { transform: scale(1); opacity: 1; }
                }
            </style>
        </head>
        <body>
            <div class="success-page">
                <i class="fas fa-paper-plane success-icon-large"></i>
                <h2 style="color: #2c3e50; margin-bottom: 15px;">Message envoyé avec succès !</h2>
                <p style="color: #34495e; font-size: 1.1rem; line-height: 1.5; max-width: 600px;">
                    Merci pour votre solidarité ! Votre proposition a bien été transmise à <strong><?php echo htmlspecialchars($req_firstName); ?></strong>.
                </p>
                
                <div class="redirect-text">
                    <i class="fas fa-spinner fa-spin"></i> Retour aux annonces dans <strong id="countdown">4</strong> secondes...
                </div>
                
                <a href="index.php?page=offerHelp" class="btn-submit" style="display: inline-block; width: auto; margin-top: 25px; padding: 12px 30px; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 5px;">
                    Retourner aux annonces immédiatement
                </a>
            </div>

            <script>
                let seconds = 4;
                const countdownElement = document.getElementById('countdown');
                const interval = setInterval(function() {
                    seconds--;
                    if (seconds >= 0) {
                        countdownElement.textContent = seconds;
                    }
                    if (seconds <= 0) {
                        clearInterval(interval);
                    }
                }, 1000);
            </script>
        </body>
        </html>
        <?php
        exit;
    } else {
        echo "<div style='color:#cc0000; background:#ffcccc; padding:15px; border-radius: 5px; margin: 20px auto; max-width: 800px; text-align: center;'>";
        echo "<strong><i class='fas fa-exclamation-triangle'></i> Erreur d'envoi :</strong><br>";
        if ($checkpoint === true){
            echo "Vous avez deja repondu a cette requette d'aide";
        }
        else {
            echo $result;
            print_r($InsertHelpAct);
            echo $checkpoint;
            echo "Une erreur est survenue lors de l'envoi de votre proposition d'aide. Veuillez réessayer plus tard.";
        }
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
                
                <?php if (!$user_id){ ?>
                    <h3>Prêt à aider ?</h3>
                    <p class="action-desc">Vous devez être connecté à votre compte pour répondre à cette demande.</p>
                    <a href="index.php?page=login" class="btn-submit">Se connecter</a>
                    <a href="index.php?page=signUp" class="btn-outline" style="margin-top: 10px; text-align: center; display: block;">Créer un compte</a>
                
                <?php }elseif ($req_status !== 'open'){ ?>
                    <h3>Demande pourvue</h3>
                    <p class="action-desc">Cette demande d'aide a déjà été acceptée par un autre bénévole ou fermée par l'utilisateur.</p>
                    <button class="btn-submit" disabled style="background: #cbd5e1; cursor: not-allowed; box-shadow: none;">Action indisponible</button>

                <?php } else{
                     ?>
                    <h3>Proposer mon aide</h3>
                    <p class="action-desc">Envoyez un petit message à <?php echo explode(' ', $req_name)[0]; ?> pour lui dire comment vous pouvez l'aider.</p>
                    
                    <form method="POST" action="index.php?page=oneHelpOffer&idR=<?php echo htmlspecialchars($idR); ?>" class="proposal-form">
                        <div class="form-group">
                            <textarea name="firstMessage" rows="5" placeholder="Bonjour, je suis disponible ce samedi pour vous aider avec..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Envoyer ma proposition
                        </button>
                    </form>
                    <p class="security-note"><i class="fas fa-shield-alt"></i> Vos coordonnées ne seront partagées que si votre aide est acceptée.</p>
               <?php } ?>
            </div>
        </div>
    </div>
</div>