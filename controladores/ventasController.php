<?php
require_once __DIR__ . '/../lib/dao_ventas.php';

class VentaController {
    private $dao;

    public function __construct($pdo) {
        $this->dao = new VentaDAO($pdo);
    }

    public function handleRequest() {
        // 🔹 Eliminar venta
        if (isset($_GET['del'])) {
            $id = intval($_GET['del']);
            $this->dao->delete($id);
            header("Location: sales.php?msg=deleted");
            exit;
        }

        // 🔹 Mostrar todas las ventas
        return $this->dao->getAll();
    }

    // 🔹 Mostrar una venta con sus detalles
    public function verDetalle($id) {
        return $this->dao->getById($id);
    }
}
