<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('./src/db_config.php');
require_once('./src/functions.php');

$mon_id = $_SESSION['user_id'] ?? null;
$dest_id = $_GET['dest_id'] ?? null;

if (!$mon_id || !$dest_id) {
    die("<div style='text-align:center; padding: 50px; font-family: sans-serif;'>
            <h3>Erreur : Impossible d'ouvrir la discussion. Utilisateurs non identifiés.</h3>
            <a href='index.php' style='color: #28B463; text-decoration: none;'>Retour à l'accueil</a>
         </div>");
}

$mes_infos_brutes = getAllUserInfo($mon_id);
$dest_infos_brutes = getAllUserInfo($dest_id);

if (is_string($mes_infos_brutes) || empty($mes_infos_brutes) || is_string($dest_infos_brutes) || empty($dest_infos_brutes)) {
    die("<div style='text-align:center; padding: 50px; font-family: sans-serif;'>
            <h3>Erreur : Données utilisateurs introuvables.</h3>
         </div>");
}

$mes_infos = $mes_infos_brutes[0];
$dest_infos = $dest_infos_brutes[0];

$mon_nom = $mes_infos['fName'] . ' ' . $mes_infos['sName'];
$dest_nom = $dest_infos['fName'] . ' ' . $dest_infos['sName'];

$id_min = min($mon_id, $dest_id);
$id_max = max($mon_id, $dest_id);
$conversation_id = "chat_" . $id_min . "_" . $id_max;
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@talkjs/web-components@0.1.10/default.css"/>
<script type="importmap">
{
    "imports": {
        "@talkjs/web-components": "https://cdn.jsdelivr.net/npm/@talkjs/web-components@0.1.10",
        "@talkjs/core": "https://cdn.jsdelivr.net/npm/@talkjs/core@1.9.1"
    }
}
</script>

<script type="module" async>
    import '@talkjs/web-components';
    import { getTalkSession } from '@talkjs/core';

    const appId = 'tG2pzpiO'; 

    const userId = '<?php echo $mon_id; ?>';
    const userName = '<?php echo addslashes($mon_nom); ?>';
    const userPhoto = '<?php echo $mes_infos['photo'] ? htmlspecialchars($mes_infos['photo']) : "https://ui-avatars.com/api/?name=".urlencode($mon_nom)."&background=28B463&color=fff"; ?>';
    
    const otherUserId = '<?php echo $dest_id; ?>';
    const otherUserName = '<?php echo addslashes($dest_nom); ?>';
    const otherUserPhoto = '<?php echo $dest_infos['photo'] ? htmlspecialchars($dest_infos['photo']) : "https://ui-avatars.com/api/?name=".urlencode($dest_nom)."&background=3b82f6&color=fff"; ?>';
    
    const conversationId = '<?php echo $conversation_id; ?>';

    const session = getTalkSession({ appId, userId });

    session.currentUser.createIfNotExists({ 
        name: userName,
        photoUrl: userPhoto 
    });
    
    session.user(otherUserId).createIfNotExists({ 
        name: otherUserName,
        photoUrl: otherUserPhoto 
    });

    const conversation = session.conversation(conversationId);
    conversation.createIfNotExists();
    conversation.participant(otherUserId).createIfNotExists();
</script>

<t-popup
    app-id="tG2pzpiO"
    user-id="<?php echo $mon_id; ?>"
    conversation-id="<?php echo $conversation_id; ?>"
></t-popup>