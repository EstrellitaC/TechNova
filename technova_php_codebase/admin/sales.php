<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';
$ventas = $pdo->query("SELECT v.*, u.nombre as cliente FROM ventas v JOIN usuarios u ON u.id=v.id_usuario ORDER BY v.id DESC")->fetchAll();
?>
<h3>Ventas</h3>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th></tr></thead>
  <tbody>
  <?php foreach($ventas as $v): ?>
    <tr><td><?=$v['id']?></td><td><?=$v['cliente']?></td><td><?=$v['fecha']?></td><td>S/ <?=number_format($v['total'],2)?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
