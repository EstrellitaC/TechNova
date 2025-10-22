<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';
$users = $pdo->query("SELECT id, nombre, correo, rol FROM usuarios ORDER BY id DESC")->fetchAll();
?>
<h3>Clientes/Usuarios</h3>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th></tr></thead>
  <tbody>
  <?php foreach($users as $u): ?>
    <tr><td><?=$u['id']?></td><td><?=$u['nombre']?></td><td><?=$u['correo']?></td><td><?=$u['rol']?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
