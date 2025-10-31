<?php
class VentaDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $sql = "SELECT v.id, v.id_usuario, v.total, v.fecha, u.nombre AS cliente
                FROM ventas v
                LEFT JOIN usuarios u ON v.id_usuario = u.id
                ORDER BY v.id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM ventas WHERE id=?");
        $stmt->execute([$id]);
        $venta = $stmt->fetch();

        $stmt2 = $this->pdo->prepare("
            SELECT dv.*, p.nombre, (dv.cantidad * dv.precio) AS subtotal
            FROM detalle_venta dv
            JOIN productos p ON dv.id_producto = p.id
            WHERE dv.id_venta=?
        ");
        $stmt2->execute([$id]);
        $venta['detalles'] = $stmt2->fetchAll();

        return $venta;
    }

    public function create($id_usuario, $total, $detalles) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO ventas (id_usuario, total, fecha) VALUES (?, ?, NOW())");
            $stmt->execute([$id_usuario, $total]);
            $idVenta = $this->pdo->lastInsertId();

            $stmtDetalle = $this->pdo->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
            foreach ($detalles as $d) {
                $stmtDetalle->execute([$idVenta, $d['id_producto'], $d['cantidad'], $d['precio']]);
            }

            $this->pdo->commit();
            return $idVenta;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM ventas WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>