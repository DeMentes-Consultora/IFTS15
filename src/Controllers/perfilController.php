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
        $filtros = [
            'id_carrera' => isset($_GET['id_carrera']) ? (int)$_GET['id_carrera'] : 0,
            'id_materia' => isset($_GET['id_materia']) ? (int)$_GET['id_materia'] : 0,
            'id_anio_cursada' => isset($_GET['id_anio_cursada']) ? (int)$_GET['id_anio_cursada'] : 0,
        ];
        $datosPerfil = PerfilService::obtenerDatosPerfil($this->conn, $id_usuario, $filtros);
        if (!$datosPerfil) {
            $error = 'No se encontraron datos de perfil.';
            include __DIR__ . '/../Views/perfil.php';
            exit;
        }
        include __DIR__ . '/../Views/perfil.php';
    }

}

if (isset($_GET['action']) && $_GET['action'] === 'actualizar_matricula') {
    $controller = new perfilController();
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_rol'])) {
        echo json_encode(['success' => false, 'message' => 'No autenticado.']);
        exit;
    }

    if ((int)$_SESSION['id_rol'] !== 2) {
        echo json_encode(['success' => false, 'message' => 'Solo profesores pueden realizar esta acción.']);
        exit;
    }

    $payload = $_POST;
    if (empty($payload)) {
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $payload = $decoded;
        }
    }

    $idAlumno = (int)($payload['id_alumno'] ?? 0);
    $idMateria = (int)($payload['id_materia'] ?? 0);
    $matriculado = (int)($payload['matriculado'] ?? 0) === 1;

    $resultado = PerfilService::actualizarEstadoMatricula(
        $controller->conn,
        (int)$_SESSION['id_usuario'],
        $idAlumno,
        $idMateria,
        $matriculado
    );

    echo json_encode($resultado);
    exit;
}

// Acción AJAX para cambiar la foto de perfil
if (isset($_GET['action']) && $_GET['action'] === 'cambiar_foto') {
    $controller = new perfilController();
    header('Content-Type: application/json; charset=utf-8');
    $maxFileSize = 2 * 1024 * 1024;
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'error' => 'No autenticado']);
        exit;
    }
    if (!isset($_FILES['nueva_foto'])) {
        echo json_encode(['success' => false, 'error' => 'Archivo no recibido.']);
        exit;
    }

    if ($_FILES['nueva_foto']['error'] !== UPLOAD_ERR_OK) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'La imagen supera el tamaño máximo permitido por el servidor.',
            UPLOAD_ERR_FORM_SIZE => 'La imagen supera el tamaño máximo permitido por el formulario.',
            UPLOAD_ERR_PARTIAL => 'La imagen se subió de forma incompleta.',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ninguna imagen.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal del servidor.',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir la imagen en disco.',
            UPLOAD_ERR_EXTENSION => 'Una extensión del servidor bloqueó la subida.'
        ];
        $errorCode = (int)$_FILES['nueva_foto']['error'];
        echo json_encode(['success' => false, 'error' => $uploadErrors[$errorCode] ?? 'Error de subida no identificado.']);
        exit;
    }

    if ((int)$_FILES['nueva_foto']['size'] <= 0) {
        echo json_encode(['success' => false, 'error' => 'La imagen recibida está vacía.']);
        exit;
    }

    if ((int)$_FILES['nueva_foto']['size'] > $maxFileSize) {
        echo json_encode(['success' => false, 'error' => 'La imagen no puede superar los 2 MB.']);
        exit;
    }

    $fileTmpPath = $_FILES['nueva_foto']['tmp_name'];
    if (!is_uploaded_file($fileTmpPath)) {
        echo json_encode(['success' => false, 'error' => 'No se pudo validar la imagen subida.']);
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = $finfo ? finfo_file($finfo, $fileTmpPath) : false;
    if ($finfo) {
        finfo_close($finfo);
    }

    if (!$mimeType || !in_array($mimeType, $allowedMimeTypes, true)) {
        echo json_encode(['success' => false, 'error' => 'Formato inválido. Solo se permiten JPG, PNG o WEBP.']);
        exit;
    }

    if (@getimagesize($fileTmpPath) === false) {
        echo json_encode(['success' => false, 'error' => 'El archivo seleccionado no es una imagen válida.']);
        exit;
    }

    require_once __DIR__ . '/../Model/Person.php';
    require_once __DIR__ . '/../Services/CloudinaryService.php';
    $id_usuario = $_SESSION['id_usuario'];
    // Buscar id_persona del usuario
    require_once __DIR__ . '/../Model/User.php';
    $user = \App\Model\User::obtenerUsuarioCompleto($controller->conn, (int)$id_usuario);
    if (!$user || empty($user['id_persona'])) {
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        exit;
    }
    $id_persona = (int)$user['id_persona'];
    $person = \App\Model\Person::buscarPorId($controller->conn, $id_persona);
    if (!$person) {
        echo json_encode(['success' => false, 'error' => 'Persona no encontrada']);
        exit;
    }
    $fileName = $_FILES['nueva_foto']['name'];
    try {
        $cloudinary = new \App\Services\CloudinaryService();
        $uploadResult = $cloudinary->uploadProfileImage($fileTmpPath, $fileName, 'ifts15/perfiles');
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
