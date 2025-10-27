<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controladores/productoController.php';
include __DIR__ . '/../includes/header.php';

$controller = new ProductoController($pdo);
$prods = $controller->handleRequest();
?>

<h3 class="mb-4">Gestión de Productos</h3>

<div class="d-flex justify-content-between align-items-center mb-3">
  <!-- Buscador -->
  <form class="d-flex" method="get" style="max-width: 300px;">
    <input type="text" class="form-control me-2" name="q" placeholder="Buscar producto..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    <button class="btn btn-outline-primary">Buscar</button>
  </form>

  <!--Botón para agregar -->
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
    <i class="bi bi-plus-lg"></i> Nuevo Producto
  </button>
</div>

<!--Tabla de productos -->
<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Imagen</th>
        <th>Producto</th>
        <th>Categoría</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($prods)): ?>
        <?php foreach ($prods as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td>
              <?php if (!empty($p['imagen'])): ?>
                <img src="../uploads/<?= htmlspecialchars($p['imagen']) ?>" alt="" style="width:50px; height:50px; object-fit:cover;">
              <?php else: ?>
                <span class="text-muted">Sin imagen</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= htmlspecialchars($p['categoria']) ?></td>
            <td>S/ <?= number_format($p['precio'], 2) ?></td>
            <td><?= $p['stock'] ?></td>
            <td>
              <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#edit<?= $p['id'] ?>">Editar</button>
              <a class="btn btn-sm btn-danger" href="products.php?del=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
            </td>
          </tr>

          <!-- Modal de edición -->
          <div class="modal fade" id="edit<?= $p['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" enctype="multipart/form-data">
                  <div class="modal-header">
                    <h5 class="modal-title">Editar producto #<?= $p['id'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="update" value="1">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <div class="mb-3">
                      <label class="form-label">Nombre</label>
                      <input class="form-control" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Descripción</label>
                      <textarea class="form-control" name="descripcion"><?= htmlspecialchars($p['descripcion']) ?></textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Categoría</label>
                      <input class="form-control" name="categoria" value="<?= htmlspecialchars($p['categoria']) ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Precio</label>
                      <input type="number" step="0.01" class="form-control" name="precio" value="<?= $p['precio'] ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Stock</label>
                      <input type="number" class="form-control" name="stock" value="<?= $p['stock'] ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Imagen</label>
                      <input type="file" class="form-control" name="imagen" accept="image/*">
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
      <?php else: ?>
        <tr><td colspan="7" class="text-center text-muted">No hay productos</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal para agregar producto -->
<div class="modal fade" id="modalAgregar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Agregar nuevo producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="create" value="1">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" placeholder="Nombre del producto" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" placeholder="Descripción"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Categoría</label>
            <input class="form-control" name="categoria" placeholder="Categoría" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" name="precio" placeholder="Precio" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" placeholder="Stock" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Imagen</label>
            <input type="file" class="form-control" name="imagen" accept="image/*">
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