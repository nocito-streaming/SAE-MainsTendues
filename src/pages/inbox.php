<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('./src/db_config.php');
require_once('./src/functions.php');

$mon_id = $_SESSION['user_id'] ?? null;
$dest_id = $_GET['dest_id'] ?? null; 

if (!$mon_id) {
    header("Location: index.php?page=login");
    exit;
}

$mes_infos_brutes = getAllUserInfo($mon_id);
if (is_string($mes_infos_brutes) || empty($mes_infos_brutes)) {
    die("<div style='text-align:center; padding: 50px;'><h3>Erreur de profil.</h3></div>");
}
$mes_infos = $mes_infos_brutes[0];
$mon_nom = $mes_infos['fName'] . ' ' . $mes_infos['sName'];

$conversation_id = null;
$dest_nom = "Utilisateur";
$dest_photo = "";

if ($dest_id) {
    $dest_infos_brutes = getAllUserInfo($dest_id);
    if (!is_string($dest_infos_brutes) && !empty($dest_infos_brutes)) {
        $dest_infos = $dest_infos_brutes[0];
        $dest_nom = $dest_infos['fName'] . ' ' . $dest_infos['sName'];
        $dest_photo = $dest_infos['photo'] ?? "";
        
        $id_min = min($mon_id, $dest_id);
        $id_max = max($mon_id, $dest_id);
        $conversation_id = "chat_" . $id_min . "_" . $id_max;
    }
}
?>

<link rel="stylesheet" href="./assets/css/inbox.css">
<div class="page-header">
    <div class="header-content">
        <h2>Ma Messagerie</h2>
    </div>
</div>

<div class="wrap" style="display: flex; justify-content: center; padding: 40px 20px; background-color: #f1f5f9; min-height: 80vh;">
    
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
        
        const session = getTalkSession({ appId, userId });

        session.currentUser.createIfNotExists({ 
            name: userName,
            photoUrl: userPhoto 
        });

        <?php if ($dest_id && $conversation_id): ?>
            const otherUserId = '<?php echo $dest_id; ?>';
            const otherUserName = '<?php echo addslashes($dest_nom); ?>';
            const otherUserPhoto = '<?php echo $dest_photo ? htmlspecialchars($dest_photo) : "https://ui-avatars.com/api/?name=".urlencode($dest_nom)."&background=3b82f6&color=fff"; ?>';
            
            session.user(otherUserId).createIfNotExists({ 
                name: otherUserName,
                photoUrl: otherUserPhoto 
            });

            const conversation = session.conversation('<?php echo $conversation_id; ?>');
            conversation.createIfNotExists();
            conversation.participant(otherUserId).createIfNotExists();
        <?php endif; ?>
    </script>

    <t-inbox
        style="width: 100%; max-width: 1200px; height: 75vh; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden;"
        app-id="tG2pzpiO"
        user-id="<?php echo $mon_id; ?>"
        <?php if ($conversation_id): ?> conversation-id="<?php echo $conversation_id; ?>" <?php endif; ?>
    ></t-inbox>

</div>