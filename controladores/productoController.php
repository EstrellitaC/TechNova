<?php
require_once __DIR__ . '/../lib/models/dao_productos.php';

class ProductoController {
    private $dao;

    public function __construct($pdo) {
        $this->dao = new ProductoDAO($pdo);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
            $this->dao->crearProducto($_POST, $_FILES);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $this->dao->actualizarProducto($_POST, $_FILES);
        }

        if (isset($_GET['del'])) {
            $this->dao->eliminarProducto($_GET['del']);
            header("Location: products.php");
            exit;
        }

        $search = $_GET['q'] ?? '';
        if (!empty($search)) {
            return $this->dao->buscarProductos($search);
        }
        
        return $this->dao->listarProductos();
    }
}


