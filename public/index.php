<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

$search = $_GET['q'] ?? '';           // texto de búsqueda
$categoria = $_GET['categoria'] ?? ''; // filtro por categoría

if($search && $categoria){
    // buscar por nombre Y categoría
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE nombre LIKE ? AND categoria = ? ORDER BY id DESC");
    $stmt->execute(['%'.$search.'%', $categoria]);
} elseif($search){
    // solo por nombre
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE nombre LIKE ? ORDER BY id DESC");
    $stmt->execute(['%'.$search.'%']);
} elseif($categoria){
    // solo por categoría
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria = ? ORDER BY id DESC");
    $stmt->execute([$categoria]);
} else {
    // sin filtro ni búsqueda
    $stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
}
$productos = $stmt->fetchAll();
?>
<div class="d-flex align-items-center mb-3">
  <h3 class="me-auto">Catálogo</h3>
<form method="get" class="d-flex mb-4" style="gap: 10px;">
  <input type="text" name="q" class="form-control" placeholder="Buscar producto..." 
      value="<?= htmlspecialchars($search) ?>">

  <select name="categoria" class="form-select" onchange="this.form.submit()">
    <option value="">Todas las categorías</option>
    <?php
    $cats = $pdo->query("SELECT DISTINCT categoria FROM productos")->fetchAll();
    foreach($cats as $c):
    ?>
      <option value="<?= htmlspecialchars($c['categoria']) ?>"
        <?= ($categoria == $c['categoria']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['categoria']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button class="btn btn-primary">Buscar</button>
</form>
</div>
<div class="row">
<?php foreach($productos as $p): ?>
  <div class="col-md-3">
    <div class="card">
      <?php if (!empty($p['imagen'])): ?>
      <img src="../uploads/<?= htmlspecialchars($p['imagen']) ?>" class="img-fluid" alt="producto">
        <?php else: ?>
          <span class="text-muted">Sin imagen</span>
        <?php endif; ?>
      <div class="card-body">
        <h5 class="card-title"><?=htmlspecialchars($p['nombre'])?></h5>
        <p class="card-text small"><?=htmlspecialchars(substr($p['descripcion'],0,80))?></p>
        <p class="fw-bold">S/ <?=number_format($p['precio'],2)?></p>
        <a href="product.php?id=<?=$p['id']?>" class="btn">Ver producto</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
