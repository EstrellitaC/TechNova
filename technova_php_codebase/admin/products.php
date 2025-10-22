<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['create'])){
        $pdo->prepare("INSERT INTO productos(nombre,descripcion,precio,stock) VALUES(?,?,?,?)")
            ->execute([$_POST['nombre'],$_POST['descripcion'],$_POST['precio'],$_POST['stock']]);
    } elseif(isset($_POST['update'])){
        $pdo->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?")
            ->execute([$_POST['nombre'],$_POST['descripcion'],$_POST['precio'],$_POST['stock'],$_POST['id']]);
    }
}
if(isset($_GET['del'])){
    $pdo->prepare("DELETE FROM productos WHERE id=?")->execute([intval($_GET['del'])]);
    header("Location: products.php"); exit;
}

$prods = $pdo->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll();
?>
<h3>Productos</h3>
<div class="row">
  <div class="col-md-5">
    <div class="card card-body">
      <h5>Nuevo producto</h5>
      <form method="post">
        <input type="hidden" name="create" value="1">
        <div class="mb-2"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
        <div class="mb-2"><textarea class="form-control" name="descripcion" placeholder="Descripción"></textarea></div>
        <div class="mb-2"><input type="number" step="0.01" class="form-control" name="precio" placeholder="Precio" required></div>
        <div class="mb-2"><input type="number" class="form-control" name="stock" placeholder="Stock" required></div>
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
  <div class="col-md-7">
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th></th></tr></thead>
      <tbody>
      <?php foreach($prods as $p): ?>
        <tr>
          <td><?=$p['id']?></td>
          <td><?=$p['nombre']?></td>
          <td>S/ <?=number_format($p['precio'],2)?></td>
          <td><?=$p['stock']?></td>
          <td>
            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#edit<?=$p['id']?>">Editar</button>
            <a class="btn btn-sm btn-danger" href="products.php?del=<?=$p['id']?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
          </td>
        </tr>
        <div class="modal fade" id="edit<?=$p['id']?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post">
                <div class="modal-header"><h5 class="modal-title">Editar producto #<?=$p['id']?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                  <input type="hidden" name="update" value="1">
                  <input type="hidden" name="id" value="<?=$p['id']?>">
                  <div class="mb-2"><input class="form-control" name="nombre" value="<?=$p['nombre']?>"></div>
                  <div class="mb-2"><textarea class="form-control" name="descripcion"><?=$p['descripcion']?></textarea></div>
                  <div class="mb-2"><input type="number" step="0.01" class="form-control" name="precio" value="<?=$p['precio']?>"></div>
                  <div class="mb-2"><input type="number" class="form-control" name="stock" value="<?=$p['stock']?>"></div>
                </div>
                <div class="modal-footer"><button class="btn btn-primary">Guardar</button></div>
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
