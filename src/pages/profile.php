<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $db;
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id){
    header("Location: index.php?page=login");
    exit;
}
$information = getAllUserInfo($user_id);

?>

<h1>Page de profile</h1>
<div class = "ChangerInfo" >
    <div class = "Adresse" ></div>
</div>