<?php
require_once __DIR__ . '../../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../lib/auth.php';
include __DIR__ . '/../includes/header.php';

$uid = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT ci.id as iditem, p.*, ci.cantidad FROM cart_items ci JOIN productos p ON p.id=ci.id_producto WHERE ci.id_usuario=?");
$stmt->execute([$uid]);
$items = $stmt->fetchAll();
$total = 0;
?>
<h3>Carrito</h3>
<?php if(!$items): ?>
  <div class="alert alert-info">Tu carrito está vacío.</div>
<?php else: ?>
<table class="table table-striped">
  <thead><tr><th>Producto</th><th>Precio</th><th>Cant.</th><th>Subtotal</th><th></th></tr></thead>
  <tbody>
  <?php foreach($items as $it): $sub = $it['precio']*$it['cantidad']; $total+=$sub; ?>
    <tr>
      <td><?=htmlspecialchars($it['nombre'])?></td>
      <td>S/ <?=number_format($it['precio'],2)?></td>
      <td><?=$it['cantidad']?></td>
      <td>S/ <?=number_format($sub,2)?></td>
      <td><a class="btn btn-sm btn-danger" href="remove_from_cart.php?id=<?=$it['iditem']?>">Quitar</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="d-flex justify-content-end">
  <div class="text-end">
    <h4>Total: S/ <?=number_format($total,2)?></h4>
    <a class="btn btn-success" href="checkout.php">Confirmar compra</a>
  </div>
</div>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
