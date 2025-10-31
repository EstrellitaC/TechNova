<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    echo "<div class='alert alert-warning text-center my-5'>Producto no encontrado.</div>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$rel = $pdo->prepare("SELECT id, nombre, precio, imagen FROM productos WHERE categoria = ? AND id != ? LIMIT 4");
$rel->execute([$p['categoria'], $p['id']]);
$relacionados = $rel->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
  <div class="row align-items-center">
    <div class="col-md-5 text-center">
      <?php if (!empty($p['imagen'])): ?>
        <img src="../uploads/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="img-fluid rounded shadow-sm" style="max-height:400px; object-fit:cover;">
      <?php else: ?>
        <div class="text-muted">Sin imagen disponible</div>
      <?php endif; ?>
    </div>
    
    <div class="col-md-7">
      <h2 class="fw-bold mb-3"><?= htmlspecialchars($p['nombre']) ?></h2>
      <p class="text-muted"><?= nl2br(htmlspecialchars($p['descripcion'])) ?></p>
      <h4 class="mb-4">S/ <?= number_format($p['precio'], 2) ?></h4>

      <form method="post" action="add_to_cart.php" class="d-flex align-items-center gap-2">
        <input type="hidden" name="id_producto" value="<?= $p['id'] ?>">
        <div>
          <div class="input-group" style="width: 130px;">
            <span class="input-group-text">Cant.</span>
            <input type="number" name="cantidad" min="1" value="1" class="form-control" required>
          </div>
          <button class="btn btn-success mt-4">Agregar al carrito</button>
        </div>
      </form>
    </div>
  </div>

  <?php if ($relacionados): ?>
    <hr class="my-5">
    <h4 class="text-center mb-4">Productos relacionados</h4>
    <div class="row g-4">
      <?php foreach ($relacionados as $r): ?>
        <div class="col-md-3 col-6">
          <div class="card h-100 border-0 shadow-sm">
            <?php if (!empty($r['imagen'])): ?>
              <img src="../uploads/<?= htmlspecialchars($r['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($r['nombre']) ?>" style="height:200px; object-fit:cover;">
            <?php else: ?>
              <div class="text-center text-muted py-5">Sin imagen</div>
            <?php endif; ?>
            <div class="card-body text-center">
              <h6 class="card-title"><?= htmlspecialchars($r['nombre']) ?></h6>
              <p class="fw-bold mb-2">S/ <?= number_format($r['precio'], 2) ?></p>
              <a href="product.php?id=<?= $r['id'] ?>" class="btn btn-dark btn-sm">Ver producto</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>