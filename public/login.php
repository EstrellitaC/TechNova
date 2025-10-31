<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
if(is_logged()) redirect('index.php');

$error = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = $_POST['correo'] ?? '';
    $pass = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo=? LIMIT 1");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if($u && password_verify($pass, $u['password'])){
        $_SESSION['user'] = $u;
        redirect('index.php');
    } else {
        $error = 'Credenciales inválidas';
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3>Iniciar sesión</h3>
    <?php if($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">Ingresar</button>
      <a href="register.php" class="btn btn-link">Crear cuenta</a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>



