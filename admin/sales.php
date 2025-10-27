<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../controladores/ventasController.php';

// Instanciar controller
$controller = new VentaController($pdo);

// Obtener todas las ventas
$ventas = $controller->handleRequest();
?>

<h3>Ventas</h3>
<table class="table table-striped">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>Total</th>
      <th>Detalles</th>
      <th>Acción</th>
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
        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detalleModal<?= $v['id'] ?>">
          Ver detalles
        </button>
      </td>
      <td>
        <a href="sales.php?del=<?= $v['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminar esta venta?')">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php
// Crear modales para cada venta
foreach($ventas as $v):
  $detalle = $controller->verDetalle($v['id']);
?>
<div class="modal fade" id="detalleModal<?= $v['id'] ?>" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles de la venta #<?= $v['id'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if (!empty($detalle['detalles'])): ?>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($detalle['detalles'] as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['nombre']) ?></td>
              <td><?= $p['cantidad'] ?></td>
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