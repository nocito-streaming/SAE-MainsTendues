<?php
function login_user($email, $password){
    global $db;

    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['pwdHash'])){
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $_SESSION["user_id"] = $user['user_id'];
        $_SESSION["email"] = $user['email'];
        $_SESSION["nickname"] = $user['nickname'];
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