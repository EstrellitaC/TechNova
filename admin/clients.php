<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controladores/usuarioController.php';
include __DIR__ . '/../includes/header.php';

//Nuevo objeto
$controller = new UsuarioController($pdo);

//Significa que se agarra todos los usuarios
$usuarios = $controller->handleRequest();

// Esto permite la busqueda de usuarios por nombre o correo
$busqueda = $_GET['q'] ?? '';
if ($busqueda) {
  $usuarios = array_filter($usuarios, fn($u) => stripos($u['nombre'], $busqueda) !== false || stripos($u['correo'], $busqueda) !== false);
}
?>

<h3 class="mb-4">Usuarios</h3>

<div class="d-flex justify-content-between align-items-center mb-3">

  <form method="get" class="d-flex mb-4" style="gap: 10px; max-width: 400px;">
    <input type="text" name="q" class="form-control" placeholder="Buscar por nombre / correo" value="<?= htmlspecialchars($busqueda) ?>">
    <button class="btn btn-primary">Buscar</button>
  </form>

  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevoUsuario">
    <i class="bi bi-person-plus"></i> Nuevo usuario
  </button>
</div>

<table class="table table-striped align-middle">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Correo</th>
      <th>Rol</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($usuarios as $u): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['correo']) ?></td>
        <td><?= htmlspecialchars($u['rol']) ?></td>
        <td>
          <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#edit<?= $u['id'] ?>">Editar</button>
          <a class="btn btn-sm btn-danger" href="clients.php?del=<?= $u['id'] ?>" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
        </td>
      </tr>

      <div class="modal fade" id="edit<?= $u['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post">
              <div class="modal-header">
                <h5 class="modal-title">Editar usuario #<?= $u['id'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="update" value="1">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <div class="mb-3">
                  <label>Nombre</label>
                  <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($u['nombre']) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Correo</label>
                  <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($u['correo']) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Rol</label>
                  <select class="form-select" name="rol" required>
                    <option value="cliente" <?= $u['rol'] == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                    <option value="admin" <?= $u['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label>Nueva contraseña (opcional)</label>
                  <input type="password" class="form-control" name="password">
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary">Guardar cambios</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </tbody>
</table>

<div class="modal fade" id="nuevoUsuario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Registrar nuevo usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="create" value="1">
          <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-select" required>
              <option value="cliente">Cliente</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
