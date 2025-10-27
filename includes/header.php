<?php
// includes/header.php
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TechNova</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="/technova_php_codebase/public/index.php">TechNova</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExample07">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/technova_php_codebase/public/index.php">Productos</a></li>
        <?php if(is_logged()): ?>
            <li class="nav-item"><a class="nav-link" href="/technova_php_codebase/public/cart.php">Carrito</a></li>
        <?php endif; ?>
        <?php if(is_admin()): ?>
            <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">Administración</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if(!is_logged()): ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Ingresar</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Registrarse</a></li>
        <?php else: ?>
          <li class="nav-item"><span class="navbar-text text-white me-2">Bienvenido, <?=htmlspecialchars($_SESSION['user']['nombre'])?></span></li>
          <li class="nav-item"><a class="nav-link" href="/technova_php_codebase/public/logout.php">Salir</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
