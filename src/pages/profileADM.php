<?php
require_once './src/db_config.php';
require_once './src/auth.php';
require_once  './src/functions.php';

require_admin();
global $db;
$view = $_GET['view'] ?? 'requests';
$search = $_GET['search'] ?? '';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_request' && isset($_POST['idR'])) {
        $result = deleteHelpRequest($_POST['idR']);
        if ($result){$message = "Demande supprimée.";}
        else {$message = "Erreur lors de la suppression.";}
    }

    if ($_POST['action'] === 'delete_user' && isset($_POST['user_id'])) {
        $result = deleteUser($_POST['user_id']);
        if ($result) {$message = "Utilisateur supprimé.";}
        else{$message = "Utilisateur supprimé.";}
    }
}

if ($view === 'users') {
    $sql = "SELECT User.user_id, User.email, defUser.fName, defUser.sName, defUser.tel 
            FROM User
            JOIN defUser ON User.user_id = defUser.user_id";
    if (!empty($search)) {
        $stmt = $db->prepare($sql . " WHERE defUser.fName LIKE :search OR defUser.sName LIKE :search");
        $stmt->execute(['search' => "%$search%"]);
        $data = $stmt->fetchAll();
    } else {
        $data = $db->query($sql)->fetchAll();
    }
} else {
    $sql = "SELECT HelpRequests.*, defUser.fName, defUser.sName 
            FROM HelpRequests 
            JOIN defUser ON HelpRequests.inNeed_id = defUser.user_id";
    if (!empty($search)) {
        $stmt = $db->prepare($sql . " WHERE du.fName LIKE :search OR du.sName LIKE :search ORDER BY hr.created_at DESC");
        $stmt->execute(['search' => "%$search%"]);
        $data = $stmt->fetchAll();
    } else {
        $data = $db->query($sql . " ORDER BY HelpRequests.created_at DESC")->fetchAll();
    }
}
?>

<link rel="stylesheet" href="./assets/css/variables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="./assets/css/profileADM.css">

<div class="admin-layout">
    <nav class="admin-sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-hands-helping" style="color: #28B463; margin-right: 8px;"></i> MainsTendues</h3>
        </div>
        <ul class="nav-links">
            <li class="<?php echo $view === 'requests' ? 'active' : ''; ?>">
                <a href="index.php?page=profileADM&view=requests"><i class="fas fa-hand-holding-heart"></i> Demandes</a>
            </li>
            <li class="<?php echo $view === 'users' ? 'active' : ''; ?>">
                <a href="index.php?page=profileADM&view=users"><i class="fas fa-users"></i> Utilisateurs</a>
            </li>
        </ul>
        <div class="logout-link">
            <a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </nav>

    <main class="admin-main">
        <?php if ($message): ?>
            <div class="alert"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <header class="content-header">
            <h1><?php echo $view === 'users' ? 'Utilisateurs' : 'Demandes d\'aide'; ?></h1>

            <form method="GET" action="index.php" class="search-form">
                <input type="hidden" name="page" value="profileADM">
                <input type="hidden" name="view" value="<?php echo htmlspecialchars($view); ?>">
                <div class="search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" placeholder="Chercher par nom..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn-search">Rechercher</button>
                <?php if ($search): ?>
                    <a href="index.php?page=profileADM&view=<?php echo htmlspecialchars($view); ?>" class="btn-clear" title="Effacer"><i class="fas fa-times"></i></a>
                <?php endif; ?>
            </form>
        </header>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                <?php if ($view === 'users'): ?>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th class="text-center">Actions</th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th>Demandeur</th>
                        <th>Type</th>
                        <th>Urgence</th>
                        <th class="text-center">Actions</th>
                    </tr>
                <?php endif; ?>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-folder-open fa-3x"></i>
                            <p>Aucun résultat pour "<?php echo htmlspecialchars($search); ?>".</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <?php if ($view === 'users'): ?>
                                <td><div class="user-name"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($row['fName'] . " " . $row['sName']); ?></div></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="text-center">
                                    <form method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');" style="margin:0;">
                                        <input type="hidden" name="action" value="delete_user">
                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                        <button type="submit" class="btn-delete" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            <?php else: ?>
                                <td><div class="user-name"><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['fName'] . " " . $row['sName']); ?></div></td>
                                <td><span class="badge badge-type"><?php echo htmlspecialchars($row['hType']); ?></span></td>
                                <td>
                                    <?php 
                                        $urgency = $row['urgencyLevel'] ?? 'Normal';
                                        $badgeClass = ($urgency === 'Urgent') ? 'badge-urgent' : 'badge-normal';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($urgency); ?></span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" onsubmit="return confirm('Supprimer cette demande ?');" style="margin:0;">
                                        <input type="hidden" name="action" value="delete_request">
                                        <input type="hidden" name="idR" value="<?php echo $row['idR']; ?>">
                                        <button type="submit" class="btn-delete" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>