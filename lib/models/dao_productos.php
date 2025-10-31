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

    private function subirImagen($imagen) {
        if (!isset($imagen) || $imagen['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = pathinfo($imagen['name'], PATHINFO_EXTENSION);
        $nombre = uniqid('prod_') . '.' . $ext;

        // Guardar directamente en la carpeta raíz del proyecto
        $rutaCarpeta = __DIR__ . '/../../uploads/';
        $ruta = $rutaCarpeta . $nombre;

        if (!is_dir($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }

        move_uploaded_file($imagen['tmp_name'], $ruta);
        return $nombre;
    }
}