<?php
function login_user($email, $password){
    global $db;

    $stmt = $db->prepare("SELECT * FROM User WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['pwdHash'])){
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $_SESSION["user_id"] = $user['user_id'];
        $_SESSION["email"] = $user['email'];
        $_SESSION["nickname"] = $user['nickname'];
        $stmtAdmin = $db->prepare("SELECT user_id FROM Admin WHERE user_id = :id");
        $stmtAdmin->execute([
            "id" => $user['user_id']
        ]);
        $_SESSION["is_admin"] = (bool)$stmtAdmin->fetch();
        return true;
    }
    return false;
}
function is_logged_in(){
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
    return isset($_SESSION["user_id"]);
}
function logout_user(){
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
    session_destroy();
    $_SESSION = [];
}
function register_user($fName,$sName, $email, $password, $tel = null){
    global $db ;
    $stmt = $db -> prepare("SELECT * FROM User WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $user = $stmt->fetch();
    if($user){
        return "Utilisateur avec cette email existe deja ";
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    try {
        $db->beginTransaction();

        // 2. Insertion dans la table User (Base)
        $stmt = $db->prepare("INSERT INTO User (email, pwdHash) VALUES (:email, :pwdHash)");
        $stmt->execute([
            "email" => $email,
            "pwdHash" => $hashed_password
        ]);

        $userId = $db->lastInsertId();

        $stmt = $db->prepare("INSERT INTO defUser (user_id, fName, sName, tel, idAdr) 
                              VALUES (:user_id, :fName, :sName, :tel, 1)");
        $stmt->execute([
            "user_id" => $userId,
            "fName" => $fName,
            "sName" => $sName,
            "tel" => $tel
        ]);

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>