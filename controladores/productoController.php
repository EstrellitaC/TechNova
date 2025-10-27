<?php
require_once __DIR__ . '/../lib/models/dao_productos.php';

class ProductoController {
    private $dao;

    public function __construct($pdo) {
        $this->dao = new ProductoDAO($pdo);
    }

    public function handleRequest() {
        // Crear producto
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
            $this->dao->crearProducto($_POST, $_FILES);
        }

        // Actualizar producto
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $this->dao->actualizarProducto($_POST, $_FILES);
        }

        // Eliminar producto
        if (isset($_GET['del'])) {
            $this->dao->eliminarProducto($_GET['del']);
            header("Location: products.php");
            exit;
        }

        // 🔍 Buscar producto por nombre (si se envió "q")
        $search = $_GET['q'] ?? '';
        if (!empty($search)) {
            return $this->dao->buscarProductos($search);
        }

        // Si no hay búsqueda, listar todos
        return $this->dao->listarProductos();
    }
}