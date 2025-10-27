<?php
class ProductoDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarProductos() {
        $stmt = $this->pdo->query("SELECT * FROM productos ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function buscarProductos($texto) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR categoria LIKE ? ORDER BY id DESC");
        $stmt->execute(['%' . $texto . '%', '%' . $texto . '%']);
        return $stmt->fetchAll();
    }

    public function crearProducto($data, $files) {
        $imagen_nombre = $this->subirImagen($files['imagen']);
        $stmt = $this->pdo->prepare("INSERT INTO productos(nombre, descripcion, categoria, precio, stock, imagen) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['categoria'],
            $data['precio'],
            $data['stock'],
            $imagen_nombre
        ]);
    }

    public function actualizarProducto($data, $files) {
        $imagen_nombre = $this->subirImagen($files['imagen']);
        $id = $data['id'];

        if ($imagen_nombre) {
            $stmt = $this->pdo->prepare("UPDATE productos SET nombre=?, descripcion=?, categoria=?, precio=?, stock=?, imagen=? WHERE id=?");
            $stmt->execute([
                $data['nombre'],
                $data['descripcion'],
                $data['categoria'],
                $data['precio'],
                $data['stock'],
                $imagen_nombre,
                $id
            ]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE productos SET nombre=?, descripcion=?, categoria=?, precio=?, stock=? WHERE id=?");
            $stmt->execute([
                $data['nombre'],
                $data['descripcion'],
                $data['categoria'],
                $data['precio'],
                $data['stock'],
                $id
            ]);
        }
    }

    public function eliminarProducto($id) {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id=?");
        $stmt->execute([$id]);
    }

    private function subirImagen($imagen) {
        if (!isset($imagen) || $imagen['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = pathinfo($imagen['name'], PATHINFO_EXTENSION);
        $nombre = uniqid('prod_') . '.' . $ext;
        $ruta = __DIR__ . '/../uploads/' . $nombre;

        if (!file_exists(__DIR__ . '/../uploads')) {
            mkdir(__DIR__ . '/../uploads', 0777, true);
        }

        move_uploaded_file($imagen['tmp_name'], $ruta);
        return $nombre;
    }
}