<?php
require_once __DIR__ . '/../lib/models/dao_usuarios.php';

class UsuarioController {
    private $dao;

    public function __construct($pdo) {
        $this->dao = new UsuarioDAO($pdo);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
            $this->crearUsuario($_POST);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $this->actualizarUsuario($_POST);
        }

        if (isset($_GET['del'])) {
            $this->eliminarUsuario((int)$_GET['del']);
        }
        
        return $this->dao->obtenerTodos();
    }

    private function crearUsuario($data) {
        $nombre = trim($data['nombre']);
        $correo = trim($data['email']);
        $password = trim($data['password']);
        $rol = trim($data['rol']);

        if ($nombre && $correo && $password && $rol) {
            $this->dao->crear($nombre, $correo, $password, $rol);
        }
    }

    private function actualizarUsuario($data) {
        $id = (int)$data['id'];
        $nombre = trim($data['nombre']);
        $correo = trim($data['email']);
        $rol = trim($data['rol']);
        $password = !empty($data['password']) ? $data['password'] : null;

        if ($id && $nombre && $correo && $rol) {
            $this->dao->actualizar($id, $nombre, $correo, $rol, $password);
        }
    }

    private function eliminarUsuario($id) {
        if ($id) {
            $this->dao->eliminar($id);
        }
    }
}
