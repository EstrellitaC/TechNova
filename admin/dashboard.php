<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

$totProd = $pdo->query("SELECT COUNT(*) c FROM productos")->fetch()['c'];
$totUsers = $pdo->query("SELECT COUNT(*) c FROM usuarios WHERE rol='cliente'")->fetch()['c'];
$totVentas = $pdo->query("SELECT COUNT(*) c FROM ventas")->fetch()['c'];
$sumVentas = $pdo->query("SELECT IFNULL(SUM(total),0) s FROM ventas")->fetch()['s'];
?>
<h3 class="mb-4">Panel Administrador</h3>
<div class="mb-4">
  <a class="btn btn-primary" href="products.php">Gestionar productos</a>
  <a class="btn btn-outline-secondary" href="clients.php">Clientes</a>
  <a class="btn btn-outline-secondary" href="sales.php">Ventas</a>
  <a class="btn btn-success" href="report_csv.php">Exportar CSV</a>
</div>
<div class="row g-3">
  <div class="col-md-3"><div class="card p-3"><h5>Productos</h5><div class="display-6"><?=$totProd?></div></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Clientes</h5><div class="display-6"><?=$totUsers?></div></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Ventas</h5><div class="display-6"><?=$totVentas?></div></div></div>
  <div class="col-md-3"><div class="card p-3"><h5>Ingresos</h5><div class="display-6">S/ <?=number_format($sumVentas,2)?></div></div></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
