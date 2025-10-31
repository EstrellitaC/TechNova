<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../lib/auth.php';

$id = intval($_GET['id'] ?? 0);
$uid = $_SESSION['user']['id'];
$pdo->prepare("DELETE FROM cart_items WHERE id=? AND id_usuario=?")->execute([$id,$uid]);
redirect('cart.php');
?>




