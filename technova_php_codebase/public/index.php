<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';

$search = $_GET['q'] ?? '';
if($search){
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE nombre LIKE ? ORDER BY id DESC");
    $stmt->execute(['%'.$search.'%']);
} else {
    $stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
}
$productos = $stmt->fetchAll();
?>
<div class="d-flex align-items-center mb-3">
  <h3 class="me-auto">Catálogo</h3>
  <form class="d-flex" method="get">
    <input type="text" name="q" class="form-control me-2" placeholder="Buscar" value="<?=htmlspecialchars($search)?>">
    <button class="btn btn-outline-light btn-primary">Buscar</button>
  </form>
</div>
<div class="row">
<?php foreach($productos as $p): ?>
  <div class="col-md-3">
    <div class="card mb-3">
      <img src="assets/img/placeholder.png" class="card-img-top" alt="producto">
      <div class="card-body">
        <h5 class="card-title"><?=htmlspecialchars($p['nombre'])?></h5>
        <p class="card-text small"><?=htmlspecialchars(substr($p['descripcion'],0,80))?>...</p>
        <p class="fw-bold">S/ <?=number_format($p['precio'],2)?></p>
        <a href="product.php?id=<?=$p['id']?>" class="btn btn-sm btn-primary">Ver</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
