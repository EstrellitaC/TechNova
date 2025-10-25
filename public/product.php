<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if(!$p){
    echo "<div class='alert alert-warning'>Producto no encontrado.</div>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

// 🔹 Obtener productos relacionados (misma categoría, distinto ID)
$rel = $pdo->prepare("SELECT id, nombre, precio, imagen FROM productos WHERE categoria = ? AND id != ? LIMIT 4");
$rel->execute([$p['categoria'], $p['id']]);
$relacionados = $rel->fetchAll();
?>

<div class="rowpdt">
  <div class="imagen">
    <?php if (!empty($p['imagen'])): ?>
      <img src="../uploads/<?= htmlspecialchars($p['imagen']) ?>" alt="producto">
    <?php else: ?>
      <span class="text-muted">Sin imagen</span>
    <?php endif; ?>
  </div>

  <div class="infopdt">
    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
    <p><?= nl2br(htmlspecialchars($p['descripcion'])) ?></p>
    <p class="fs-4 text-success">S/ <?= number_format($p['precio'],2) ?></p>

    <form method="post" action="add_to_cart.php">
      <input type="hidden" name="id_producto" value="<?= $p['id'] ?>">
      <div class="input-group mb-3" style="max-width:220px;">
        <span class="input-group-text">Cant.</span>
        <input type="number" class="form-control" min="1" value="1" name="cantidad">
      </div>
      <button class="btn btn-success">Agregar al carrito</button>
    </form>
  </div>
</div>

<?php if($relacionados): ?>
  <hr>
  <h4 class="text-center mt-4 mb-4">Productos relacionados</h4>
  <div class="row justify-content-center">
    <?php foreach($relacionados as $r): ?>
      <div class="col-md-3 col-6 mb-4 text-center">
        <div class="card h-100 shadow-sm">
          <?php if (!empty($r['imagen'])): ?>
            <img src="../uploads/<?= htmlspecialchars($r['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($r['nombre']) ?>" style="height:180px; object-fit:cover;">
          <?php else: ?>
            <div class="text-muted" style="height:180px; display:flex; align-items:center; justify-content:center;">Sin imagen</div>
          <?php endif; ?>
          <div class="card-body">
            <h6 class="card-title"><?= htmlspecialchars($r['nombre']) ?></h6>
            <p class="text-primary fw-bold mb-2">S/ <?= number_format($r['precio'],2) ?></p>
            <a href="producto.php?id=<?= $r['id'] ?>" class="btn btn-outline-primary btn-sm">Ver más</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>