<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

$totProd = $pdo->query("SELECT COUNT(*) c FROM productos")->fetch()['c'];
$totUsers = $pdo->query("SELECT COUNT(*) c FROM usuarios WHERE rol='cliente'")->fetch()['c'];
$totVentas = $pdo->query("SELECT COUNT(*) c FROM ventas")->fetch()['c'];
$sumVentas = $pdo->query("SELECT IFNULL(SUM(total),0) s FROM ventas")->fetch()['s'];
$masVendidos = $pdo->query("
    SELECT p.nombre, SUM(dv.cantidad) AS total_vendido
    FROM detalle_venta dv
    JOIN productos p ON p.id = dv.id_producto
    GROUP BY p.id, p.nombre
    ORDER BY total_vendido DESC
    LIMIT 5
")->fetchAll();
$mejoresClientes = $pdo->query("
    SELECT u.nombre, COUNT(v.id) AS compras, SUM(v.total) AS gasto_total
    FROM ventas v
    JOIN usuarios u ON u.id = v.id_usuario
    GROUP BY u.id, u.nombre
    ORDER BY gasto_total DESC
    LIMIT 5
")->fetchAll();
?>

<h3 class="mb-4">Panel Administrador</h3>
<div class="mb-4">
  <a class="btn btn-primary" href="products.php">Gestionar productos</a>
  <a class="btn btn-outline-secondary" href="clients.php">Clientes</a>
  <a class="btn btn-outline-secondary" href="sales.php">Ventas</a>
  <a class="btn btn-success" href="report_csv.php">Exportar Excel</a>
</div>
<div class="row g-3">
  <div class="col-md-3"><div class="card p-3"><h5>Productos</h5><div class="display-6"><?=$totProd?></div></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Clientes</h5><div class="display-6"><?=$totUsers?></div></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Ventas</h5><div class="display-6"><?=$totVentas?></div></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Ingresos</h5><div class="display-6">S/ <?=number_format($sumVentas,2)?></div></div></div>
</div>
<div class="tablas">
    <div class="tabla1">
    <h4>Mejores clientes</h4>
    <table class="table table-bordered">
      <thead><tr><th>Cliente</th><th>Compras</th><th>Total gastado (S/)</th></tr></thead>
      <tbody>
        <?php foreach($mejoresClientes as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['nombre']) ?></td>
            <td><?= $c['compras'] ?></td>
            <td><?= number_format($c['gasto_total'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="tabla2">
    <h4>Productos más vendidos</h4>
    <table class="table table-bordered">
      <thead><tr><th>Producto</th><th>Cantidad vendida</th></tr></thead>
      <tbody>
        <?php foreach($masVendidos as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= $p['total_vendido'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
