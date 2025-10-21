<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'blood_bank';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
session_start();
function is_logged_in(){
    return isset($_SESSION['user']);
}
function require_login(){
    if (!is_logged_in()){
        header('Location: login.php');
        exit;
    }
}
?>