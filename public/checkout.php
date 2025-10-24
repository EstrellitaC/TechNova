<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../lib/auth.php';

$uid = $_SESSION['user']['id'];
$pdo->beginTransaction();
try{
    $stmt = $pdo->prepare("SELECT ci.*, p.precio, p.stock FROM cart_items ci JOIN productos p ON p.id=ci.id_producto WHERE ci.id_usuario=? FOR UPDATE");
    $stmt->execute([$uid]);
    $items = $stmt->fetchAll();
    if(!$items){ throw new Exception('Carrito vacío'); }
    $total = 0;
    foreach($items as $it){
        if($it['cantidad'] > $it['stock']) throw new Exception('Stock insuficiente en algún producto');
        $total += $it['precio']*$it['cantidad'];
    }
    $pdo->prepare("INSERT INTO ventas(id_usuario,total,fecha) VALUES(?,?,NOW())")->execute([$uid,$total]);
    $idVenta = $pdo->lastInsertId();
    $ins = $pdo->prepare("INSERT INTO detalle_venta(id_venta,id_producto,cantidad,precio) VALUES(?,?,?,?)");
    $up  = $pdo->prepare("UPDATE productos SET stock=stock-? WHERE id=?");
    foreach($items as $it){
        $ins->execute([$idVenta,$it['id_producto'],$it['cantidad'],$it['precio']]);
        $up->execute([$it['cantidad'],$it['id_producto']]);
    }
    $pdo->prepare("DELETE FROM cart_items WHERE id_usuario=?")->execute([$uid]);
    $pdo->commit();
    $_SESSION['flash'] = "Compra realizada. N° de venta: $idVenta";
} catch (Exception $e){
    $pdo->rollBack();
    $_SESSION['flash'] = "Error: " . $e->getMessage();
}
redirect('index.php');
?>