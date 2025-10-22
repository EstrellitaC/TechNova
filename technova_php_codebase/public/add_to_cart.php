<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
if(!is_logged()) redirect('login.php');

$id_producto = intval($_POST['id_producto'] ?? 0);
$cantidad = max(1, intval($_POST['cantidad'] ?? 1));
$uid = $_SESSION['user']['id'];

# Verificar producto
$st = $pdo->prepare("SELECT id, stock FROM productos WHERE id=?");
$st->execute([$id_producto]);
$prod = $st->fetch();
if(!$prod){ redirect('index.php'); }

# Insertar o actualizar item en carrito
$st = $pdo->prepare("SELECT id, cantidad FROM cart_items WHERE id_usuario=? AND id_producto=?");
$st->execute([$uid, $id_producto]);
$row = $st->fetch();
if($row){
    $new = $row['cantidad'] + $cantidad;
    $pdo->prepare("UPDATE cart_items SET cantidad=? WHERE id=?")->execute([$new, $row['id']]);
} else {
    $pdo->prepare("INSERT INTO cart_items(id_usuario,id_producto,cantidad) VALUES(?,?,?)")->execute([$uid,$id_producto,$cantidad]);
}
redirect('cart.php');
?>
