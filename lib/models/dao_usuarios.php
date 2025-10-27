<?php
class UsuarioDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 🔹 Obtener todos los usuarios
    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT id, nombre, correo, rol FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // 🔹 Crear nuevo usuario
    public function crear($nombre, $correo, $password, $rol) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $correo, $hash, $rol]);
    }

    // 🔹 Actualizar usuario
    public function actualizar($id, $nombre, $correo, $rol, $password = null) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre=?, correo=?, rol=?, password=? WHERE id=?");
            return $stmt->execute([$nombre, $correo, $rol, $hash, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre=?, correo=?, rol=? WHERE id=?");
            return $stmt->execute([$nombre, $correo, $rol, $id]);
        }
    }

    // 🔹 Eliminar usuario
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id=?");
        return $stmt->execute([$id]);
    }

    // 🔹 Obtener usuario por ID
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
