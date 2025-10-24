<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Crear o actualizar productos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 📦 SUBIR IMAGEN (si existe)
    $imagen_nombre = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen_nombre = uniqid('prod_') . '.' . $ext; // nombre único
        $ruta_destino = __DIR__ . '/../uploads/' . $imagen_nombre;

        // Crea la carpeta /uploads si no existe
        if (!file_exists(__DIR__ . '/../uploads')) {
            mkdir(__DIR__ . '/../uploads', 0777, true);
        }

        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino);
    }

    // Crear nuevo producto
    if (isset($_POST['create'])) {
        $stmt = $pdo->prepare("INSERT INTO productos(nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['precio'],
            $_POST['stock'],
            $imagen_nombre
        ]);
    }

    // Actualizar producto
    elseif (isset($_POST['update'])) {
        $id = $_POST['id'];

        // Si subió una nueva imagen, actualizamos la ruta también
        if ($imagen_nombre) {
            $stmt = $pdo->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen=? WHERE id=?");
            $stmt->execute([
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['precio'],
                $_POST['stock'],
                $imagen_nombre,
                $id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?");
            $stmt->execute([
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['precio'],
                $_POST['stock'],
                $id
            ]);
        }
    }
}

// Eliminar producto
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM productos WHERE id=?")->execute([intval($_GET['del'])]);
    header("Location: products.php");
    exit;
}

// Obtener productos
$prods = $pdo->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll();
?>

<h3>Productos</h3>
<div class="row">
  <div class="col-md-5">
    <div class="card card-body">
      <h5 class="mb-4">Nuevo producto</h5>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="create" value="1">
        <div class="mb-4">
          <input class="form-control" name="nombre" placeholder="Nombre" required>
        </div>
        <div class="mb-4">
          <textarea class="form-control" name="descripcion" placeholder="Descripción"></textarea>
        </div>
        <div class="mb-4">
          <input type="number" step="0.01" class="form-control" name="precio" placeholder="Precio" required>
        </div>
        <div class="mb-4">
          <input type="number" class="form-control" name="stock" placeholder="Stock" required>
        </div>
        <div class="mb-4">
          <input type="file" class="form-control" name="imagen" accept="image/*">
        </div>

        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>

  <div class="col-md-7">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Imagen</th>
          <th>Producto</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
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
                    <div class="mb-4">
                      <input class="form-control" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
                    </div>
                    <div class="mb-4">
                      <textarea class="form-control" name="descripcion"><?= htmlspecialchars($p['descripcion']) ?></textarea>
                    </div>
                    <div class="mb-4">
                      <input type="number" step="0.01" class="form-control" name="precio" value="<?= $p['precio'] ?>">
                    </div>
                    <div class="mb-4">
                      <input type="number" class="form-control" name="stock" value="<?= $p['stock'] ?>">
                    </div>
                    <div class="mb-4">
                      <input type="file" class="form-control" name="imagen" accept="image/*">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>