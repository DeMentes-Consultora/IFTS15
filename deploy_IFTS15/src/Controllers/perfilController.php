<?php
// src/Controllers/perfilController.php
// Controlador para mostrar el perfil del usuario logueado (alumno)


namespace App\Controllers;

require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\ConectionBD\ConectionDB;
use App\Services\PerfilService;

class perfilController {
    private $conn;
    private $db;

    public function __construct() {
        if (!function_exists('env')) {
            require_once __DIR__ . '/../config.php';
        }
        $this->db = new ConectionDB();
        $this->conn = $this->db->getConnection();
    }

    public function mostrarPerfil() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
            exit;
        }
        $id_usuario = $_SESSION['id_usuario'];
        $datosPerfil = PerfilService::obtenerDatosPerfil($this->conn, $id_usuario);
        if (!$datosPerfil) {
            $error = 'No se encontraron datos de perfil.';
            include __DIR__ . '/../Views/perfil.php';
            exit;
        }
        include __DIR__ . '/../Views/perfil.php';
    }
}

// Procesamiento directo
if (basename($_SERVER['PHP_SELF']) === 'perfilController.php') {
    $controller = new perfilController();
    $controller->mostrarPerfil();
}
