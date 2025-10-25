<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

// Obtener ventas con usuario
$ventas = $pdo->query("
  SELECT v.*, u.nombre AS cliente 
  FROM ventas v 
  JOIN usuarios u ON u.id = v.id_usuario 
  ORDER BY v.id DESC
")->fetchAll();
?>

<h3>Ventas</h3>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>Total</th>
      <th>Detalles</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($ventas as $v): ?>
    <tr>
      <td><?= $v['id'] ?></td>
      <td><?= htmlspecialchars($v['cliente']) ?></td>
      <td><?= $v['fecha'] ?></td>
      <td>S/ <?= number_format($v['total'], 2) ?></td>
      <td>
        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detalleModal<?= $v['id'] ?>">
          Ver detalles
        </button>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php
// Crear un modal para cada venta
foreach($ventas as $v):
  $stmt = $pdo->prepare("
    SELECT p.nombre, dv.cantidad, dv.precio, (dv.cantidad * dv.precio) AS subtotal
    FROM detalle_venta dv
    JOIN productos p ON p.id = dv.id_producto
    WHERE dv.id_venta = ?
  ");
  $stmt->execute([$v['id']]);
  $productos = $stmt->fetchAll();
?>
<div class="modal fade" id="detalleModal<?= $v['id'] ?>" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles de la venta #<?= $v['id'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if ($productos): ?>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($productos as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['nombre']) ?></td>
              <td><?= $p['cantidad'] ?></td>
              <td>S/ <?= number_format($p['precio'], 2) ?></td>
              <td>S/ <?= number_format($p['subtotal'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p>No hay productos registrados en esta venta.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>