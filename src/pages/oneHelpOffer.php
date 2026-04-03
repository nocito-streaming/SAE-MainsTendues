<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;
$idR = $_GET['idR'] ?? null;

// --- DUMMY DATA --- 
// (À remplacer par votre SELECT HelpRequests JOIN defUser JOIN Address WHERE idR = :idR)
$req_title = "Besoin d'aide pour monter un meuble IKEA";
$req_type = "Bricolage";
$req_urgency = "Urgent";
$req_content = "Bonjour, j'ai acheté une grande armoire PAX ce matin mais je me rends compte que c'est trop lourd et compliqué à monter seul. J'aurais besoin d'une personne avec quelques outils de base (tournevis, visseuse) pour m'aider ce week-end. Je prépare le café et les croissants ! Merci d'avance.";
$req_name = "Marie Dubois";
$req_city = "Anglet";
$req_date = "03/04/2026";
$req_status = "open";

// Traitement du formulaire de proposition d'aide
$message_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstMessage'])) {
    // Ici, votre INSERT INTO Help (volunteer_id, idR, firstMessage) VALUES (...)
    $message_success = true;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

<style>
body {
    background-color: #f4f7f6;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.page-header {
    background: linear-gradient(135deg, #28B463, #1D8348);
    color: white;
    padding: 60px 20px;
    text-align: center;
    margin-bottom: -60px;
}

.header-content h2 {
    font-size: 32px;
    margin: 0 0 10px 0;
}

.header-content p {
    font-size: 16px;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.detail-wrap {
    max-width: 1000px;
    margin: 0 auto;
    padding: 80px 20px 50px 20px;
    position: relative;
    z-index: 10;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #1e293b;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 25px;
    padding: 10px 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.btn-back:hover {
    color: #28B463;
    transform: translateX(-5px);
}

.alert-success {
    background-color: #dcfce7;
    color: #16a34a;
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.detail-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 30px;
    align-items: start;
}

.detail-card {
    background: white;
    border-radius: 16px;
    padding: 35px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.card-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.badges-group {
    display: flex;
    gap: 10px;
}

.badge {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-type {
    background: #eef2ff;
    color: #4f46e5;
}

.badge-urgent {
    background: #fee2e2;
    color: #b91c1c;
}

.badge-normal {
    background: #f1f5f9;
    color: #475569;
}

.date-posted {
    font-size: 14px;
    color: #94a3b8;
}

.detail-title {
    margin: 0 0 25px 0;
    font-size: 28px;
    color: #0f172a;
    line-height: 1.3;
}

.requester-info {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #f8fafc;
    padding: 15px 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.requester-avatar {
    width: 45px;
    height: 45px;
    background: #28B463;
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
}

.requester-text {
    display: flex;
    flex-direction: column;
}

.requester-text strong {
    color: #1e293b;
    font-size: 16px;
}

.requester-text span {
    color: #64748b;
    font-size: 14px;
    margin-top: 3px;
}

.requester-text span i {
    color: #cbd5e1;
}

.detail-divider {
    border: 0;
    height: 1px;
    background: #e2e8f0;
    margin: 30px 0;
}

.detail-description h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-description h3 i {
    color: #28B463;
}

.detail-description p {
    margin: 0;
    color: #475569;
    line-height: 1.7;
    font-size: 16px;
}

/* SIDEBAR ACTION CARD */
.action-card {
    background: white;
    border-radius: 16px;
    padding: 35px 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 20px;
    text-align: center;
}

.action-icon {
    width: 70px;
    height: 70px;
    background: #f0fdf4;
    color: #28B463;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
    margin: 0 auto 20px auto;
}

.action-card h3 {
    margin: 0 0 10px 0;
    font-size: 22px;
    color: #1e293b;
}

.action-desc {
    color: #64748b;
    font-size: 15px;
    line-height: 1.5;
    margin-bottom: 25px;
}

.proposal-form .form-group textarea {
    width: 100%;
    padding: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    background-color: #f8fafc;
    transition: all 0.3s;
    font-family: inherit;
    box-sizing: border-box;
    resize: vertical;
    margin-bottom: 15px;
}

.proposal-form .form-group textarea:focus {
    border-color: #28B463;
    background-color: white;
    box-shadow: 0 0 0 3px rgba(40, 180, 99, 0.1);
}

.btn-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #28B463, #1D8348);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
    transition: all 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    box-sizing: border-box;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px rgba(40, 180, 99, 0.3);
}

.btn-outline {
    text-decoration: none;
    color: #28B463;
    font-weight: bold;
    padding: 12px;
    display: inline-block;
    transition: 0.3s;
}

.btn-outline:hover {
    color: #1D8348;
    text-decoration: underline;
}

.security-note {
    margin-top: 20px;
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .action-card {
        position: static;
    }
}
</style>