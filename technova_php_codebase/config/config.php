<?php
// config/config.php
session_start();
define('BASE_URL', '/technova/public'); // ajusta si tu carpeta difiere
date_default_timezone_set('America/Lima');

function is_logged(){ return isset($_SESSION['user']); }
function is_admin(){ return is_logged() && $_SESSION['user']['rol']==='admin'; }

function redirect($path){
    header("Location: " . $path);
    exit;
}
?>