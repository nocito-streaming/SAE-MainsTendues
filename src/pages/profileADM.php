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
        $stmt = $db->prepare("DELETE FROM HelpRequests WHERE idR = ?");
        $stmt->execute([$_POST['idR']]);
        $message = "Demande supprimée.";
    }

    if ($_POST['action'] === 'delete_user' && isset($_POST['user_id'])) {
        $stmt = $db->prepare("DELETE FROM User WHERE user_id = ?");
        $stmt->execute([$_POST['user_id']]);
        $message = "Utilisateur supprimé.";
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

<style>
body {
    background-color: #f1f5f9;
}
.admin-layout {
    display: flex;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f1f5f9;
}

.admin-sidebar {
    width: 260px;
    background-color: #1e293b;
    color: white;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    box-shadow: 4px 0 10px rgba(0,0,0,0.05);
    z-index: 100;
}

.sidebar-header {
    padding: 30px 20px;
    border-bottom: 1px solid #334155;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 22px;
    color: #f8fafc;
    display: flex;
    align-items: center;
}

.nav-links {
    list-style: none;
    padding: 20px 0;
    margin: 0;
    flex: 1;
}

.nav-links li {
    margin-bottom: 5px;
}

.nav-links a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 25px;
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    font-weight: 500;
}

.nav-links a:hover {
    background-color: #334155;
    color: white;
}

.nav-links .active a {
    background-color: #f0fdf4;
    color: #16a34a;
    border-left-color: #28B463;
}

.logout-link {
    padding: 20px;
    border-top: 1px solid #334155;
}

.logout-link a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #ef4444;
    text-decoration: none;
    padding: 10px 5px;
    transition: color 0.3s ease;
    font-weight: 500;
}

.logout-link a:hover {
    color: #fca5a5;
}

/* --- MAIN CONTENT --- */
.admin-main {
    margin-left: 260px;
    flex: 1;
    padding: 40px;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: white;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}

.content-header h1 {
    margin: 0;
    color: #0f172a;
    font-size: 24px;
}

/* --- SEARCH FORM --- */
.search-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

.search-form input[type="text"] {
    padding: 12px 15px 12px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    width: 280px;
    outline: none;
    transition: all 0.3s ease;
    background-color: #f8fafc;
}

.search-form input[type="text"]:focus {
    border-color: #28B463;
    background-color: white;
    box-shadow: 0 0 0 3px rgba(40, 180, 99, 0.1);
}

.btn-search {
    padding: 12px 20px;
    background-color: #28B463;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-search:hover {
    background-color: #1D8348;
}

.btn-clear {
    padding: 12px 15px;
    background-color: #f1f5f9;
    color: #64748b;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-clear:hover {
    background-color: #e2e8f0;
    color: #ef4444;
}

/* --- ALERTS --- */
.alert {
    background-color: #dcfce7;
    color: #16a34a;
    padding: 16px 20px;
    border-left: 4px solid #22c55e;
    border-radius: 8px;
    margin-bottom: 25px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

/* --- TABLE --- */
.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table thead {
    background-color: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}

.admin-table th,
.admin-table td {
    padding: 18px 25px;
    text-align: left;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}

.admin-table th {
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}

.admin-table tbody tr {
    transition: background-color 0.2s ease;
}

.admin-table tbody tr:hover {
    background-color: #f8fafc;
}

.user-name {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    color: #0f172a;
}

.user-name i {
    color: #94a3b8;
    font-size: 18px;
}

.text-center {
    text-align: center !important;
}

/* --- BADGES --- */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.badge-type {
    background-color: #e0f2fe;
    color: #0369a1;
}

.badge-normal {
    background-color: #f1f5f9;
    color: #475569;
}

.badge-urgent {
    background-color: #fee2e2;
    color: #b91c1c;
}

/* --- BUTTONS --- */
.btn-delete {
    background-color: #fee2e2;
    color: #ef4444;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}

.btn-delete:hover {
    background-color: #ef4444;
    color: white;
    transform: scale(1.05);
}

.empty-state {
    text-align: center;
    padding: 60px 20px !important;
    color: #94a3b8;
}

.empty-state i {
    color: #cbd5e1;
    margin-bottom: 15px;
}

/* --- RESPONSIVE --- */
@media (max-width: 992px) {
    .admin-sidebar {
        width: 80px;
    }
    
    .sidebar-header h3 {
        display: none;
    }
    
    .nav-links a span, 
    .logout-link a span,
    .logout-link a text {
        display: none;
    }
    
    .nav-links a {
        justify-content: center;
        padding: 15px;
    }
    
    .admin-main {
        margin-left: 80px;
    }
}

@media (max-width: 768px) {
    .admin-layout {
        flex-direction: column;
    }

    .admin-sidebar {
        position: static;
        width: 100%;
        height: auto;
        flex-direction: row;
        align-items: center;
        padding: 0 15px;
    }

    .sidebar-header {
        padding: 15px 0;
        border: none;
    }

    .sidebar-header h3 {
        display: flex;
        font-size: 18px;
    }

    .nav-links {
        display: flex;
        padding: 0;
        margin-left: auto;
    }

    .nav-links a span {
        display: none;
    }

    .logout-link {
        border: none;
        padding: 15px;
    }

    .admin-main {
        margin-left: 0;
        padding: 20px;
    }

    .content-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .search-form {
        width: 100%;
    }

    .search-form input[type="text"] {
        flex: 1;
    }
    
    .table-container {
        overflow-x: auto;
    }
}
</style>