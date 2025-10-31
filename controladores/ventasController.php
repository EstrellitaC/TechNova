<?php
require_once __DIR__ . '/../lib/models/dao_ventas.php';

class VentaController {
    private $dao;

    public function __construct($pdo) {
        $this->dao = new VentaDAO($pdo);
    }

    public function handleRequest() {
        if (isset($_GET['del'])) {
            $id = intval($_GET['del']);
            $this->dao->delete($id);
            header("Location: sales.php?msg=deleted");
            exit;
        }

        return $this->dao->getAll();
    }

    public function verDetalle($id) {
        return $this->dao->getById($id);
    }
}


