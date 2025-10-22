<?php
// lib/admin_guard.php
require_once __DIR__ . '/../config/config.php';
if(!is_admin()){ redirect('../public/index.php'); }
?>