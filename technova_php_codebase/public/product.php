<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if(!$p){ echo "<div class='alert alert-warning'>Producto no encontrado.</div>"; include __DIR__ . '/../includes/footer.php'; exit; }
?>
<div class="row">
  <div class="col-md-5">
    <img src="assets/img/placeholder.png" class="img-fluid" alt="producto">
  </div>
  <div class="col-md-7">
    <h3><?=htmlspecialchars($p['nombre'])?></h3>
    <p><?=nl2br(htmlspecialchars($p['descripcion']))?></p>
    <p class="fs-4">S/ <?=number_format($p['precio'],2)?></p>
    <form method="post" action="add_to_cart.php">
      <input type="hidden" name="id_producto" value="<?=$p['id']?>">
      <div class="input-group mb-3" style="max-width:220px;">
        <span class="input-group-text">Cant.</span>
        <input type="number" class="form-control" min="1" value="1" name="cantidad">
      </div>
      <button class="btn btn-success">Agregar al carrito</button>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
