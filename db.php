<?php
require_once 'config.php';

function getPDO(){
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}

function isLoggedIn(){
    return !empty($_SESSION['user_id']);
}

function currentUser(){
    if (!isLoggedIn()) return null;
    return ['id'=>$_SESSION['user_id'],'username'=>$_SESSION['username']];
}
?>
