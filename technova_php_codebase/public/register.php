<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
if(is_logged()) redirect('index.php');

$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pass = $_POST['password'] ?? '';
    if(!$nombre || !$correo || !$pass){
        $err = 'Completa todos los campos';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo=?");
        $stmt->execute([$correo]);
        if($stmt->fetch()){ $err = 'El correo ya está registrado'; }
        else{
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $pdo->prepare("INSERT INTO usuarios(nombre,correo,password,rol) VALUES(?,?,?,'cliente')")
                ->execute([$nombre,$correo,$hash]);
            $_SESSION['user'] = [
                'id'=>$pdo->lastInsertId(),
                'nombre'=>$nombre,'correo'=>$correo,'rol'=>'cliente'
            ];
            redirect('index.php');
        }
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Crear cuenta</h3>
    <?php if($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3"><label class="form-label">Nombre</label><input class="form-control" name="nombre" required></div>
      <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="correo" required></div>
      <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" class="form-control" name="password" required></div>
      <button class="btn btn-success">Registrarme</button>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
