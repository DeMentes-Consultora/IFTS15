<?php
// src/Controllers/perfilController.php
// Controlador para mostrar el perfil del usuario logueado (alumno)

namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\ConectionBD\ConectionDB;
use App\Services\PerfilService;

class perfilController {
    public $conn;
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

// Acción AJAX para cambiar la foto de perfil
if (isset($_GET['action']) && $_GET['action'] === 'cambiar_foto') {
    $controller = new perfilController();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'error' => 'No autenticado']);
        exit;
    }
    if (!isset($_FILES['nueva_foto']) || $_FILES['nueva_foto']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Archivo no recibido o error de subida.']);
        exit;
    }
    require_once __DIR__ . '/../Model/Person.php';
    require_once __DIR__ . '/../Services/CloudinaryService.php';
    $id_usuario = $_SESSION['id_usuario'];
    // Buscar id_persona del usuario
    require_once __DIR__ . '/../Model/User.php';
    $user = \App\Model\User::buscarPorId($controller->conn, $id_usuario);
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        exit;
    }
    $id_persona = $user->getIdPersona();
    $person = \App\Model\Person::buscarPorId($controller->conn, $id_persona);
    if (!$person) {
        echo json_encode(['success' => false, 'error' => 'Persona no encontrada']);
        exit;
    }
    $fileTmpPath = $_FILES['nueva_foto']['tmp_name'];
    $fileName = $_FILES['nueva_foto']['name'];
    try {
        $cloudinary = new \App\Services\CloudinaryService();
        $uploadResult = $cloudinary->uploadImage($fileTmpPath, $fileName, 'ifts15/perfiles');
        $nueva_url = $uploadResult['secure_url'] ?? null;
        $nuevo_public_id = $uploadResult['public_id'] ?? null;
        if (!$nueva_url || !$nuevo_public_id) {
            throw new \Exception('Error al subir la imagen a Cloudinary');
        }
        // Actualizar en BD y obtener public_id anterior usando método de instancia
        $public_id_anterior = $person->actualizarFotoPerfil($controller->conn, $nueva_url, $nuevo_public_id);
        // Borrar anterior si existe
        if ($public_id_anterior && $public_id_anterior !== $nuevo_public_id) {
            $cloudinary->deleteImage($public_id_anterior);
        }
        echo json_encode(['success' => true, 'nueva_foto_url' => $nueva_url]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Procesamiento directo para mostrar perfil
if (basename($_SERVER['PHP_SELF']) === 'perfilController.php') {
    $controller = new perfilController();
    $controller->mostrarPerfil();
}
