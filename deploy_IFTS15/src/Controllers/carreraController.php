<?php
namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\ConectionBD\ConectionDB;
use App\Model\Carrera;
use Exception;

require_once __DIR__ . '/../config.php';

class CarreraController
{
    private $conn;
    private $db;

    public function __construct()
    {
        $this->db = new ConectionDB();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Respuesta JSON unificada para endpoints AJAX.
     */
    private function jsonResponse(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }

    /**
     * Listar carreras (para la vista ABM)
     */
    public function listar()
    {
        // Verificar permisos admin
        if (!isLoggedIn() || !isAdminRole()) {
            header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
            exit;
        }

        try {
            $carreras = Carrera::obtenerTodas($this->conn);
            
            // Si es petición AJAX, devolver JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                $this->jsonResponse(['success' => true, 'carreras' => $carreras]);
            }
            
            // Si no, devolver array para incluir en vista
            return $carreras;
        } catch (Exception $e) {
            error_log('[CarreraController::listar] ' . $e->getMessage());
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
            }
            return [];
        }
    }

    /**
     * Crear carrera (AJAX)
     */
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        if (!isLoggedIn() || !isAdminRole()) {
            $this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
        }

        $nombre = trim($_POST['nombre'] ?? '');
        
        if (empty($nombre)) {
            $this->jsonResponse(['success' => false, 'error' => 'El nombre es obligatorio'], 400);
        }

        try {
            $id = Carrera::crear($this->conn, $nombre);
            if ($id) {
                $this->jsonResponse(['success' => true, 'id' => $id, 'message' => 'Carrera creada exitosamente']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo crear la carrera'], 500);
            }
        } catch (Exception $e) {
            error_log('[CarreraController::crear] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    /**
     * Actualizar carrera (AJAX)
     */
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        if (!isLoggedIn() || !isAdminRole()) {
            $this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        
        if ($id <= 0 || empty($nombre)) {
            $this->jsonResponse(['success' => false, 'error' => 'Datos inválidos'], 400);
        }

        try {
            $ok = Carrera::actualizar($this->conn, $id, $nombre);
            if ($ok) {
                $this->jsonResponse(['success' => true, 'message' => 'Carrera actualizada exitosamente']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo actualizar'], 500);
            }
        } catch (Exception $e) {
            error_log('[CarreraController::actualizar] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    /**
     * Eliminar carrera (AJAX)
     */
    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        if (!isLoggedIn() || !isAdminRole()) {
            $this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
        }

        $id = intval($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            $this->jsonResponse(['success' => false, 'error' => 'ID inválido'], 400);
        }

        try {
            $ok = Carrera::eliminar($this->conn, $id);
            if ($ok) {
                $this->jsonResponse(['success' => true, 'message' => 'Carrera eliminada exitosamente']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo eliminar'], 500);
            }
        } catch (Exception $e) {
            error_log('[CarreraController::eliminar] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    /**
     * Asociar materia a carrera (drag & drop)
     */
    public function asociarMateria()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        if (!isLoggedIn() || !isAdminRole()) {
            $this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
        }

        $idCarrera = intval($_POST['id_carrera'] ?? 0);
        $idMateria = intval($_POST['id_materia'] ?? 0);
        
        if ($idCarrera <= 0 || $idMateria <= 0) {
            $this->jsonResponse(['success' => false, 'error' => 'Datos inválidos'], 400);
        }

        try {
            $result = Carrera::asociarMateria($this->conn, $idCarrera, $idMateria);
            $this->jsonResponse($result);
        } catch (Exception $e) {
            error_log('[CarreraController::asociarMateria] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    /**
     * Desasociar materia de carrera
     */
    public function desasociarMateria()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        if (!isLoggedIn() || !isAdminRole()) {
            $this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
        }

        $idCarrera = intval($_POST['id_carrera'] ?? 0);
        $idMateria = intval($_POST['id_materia'] ?? 0);
        
        if ($idCarrera <= 0 || $idMateria <= 0) {
            $this->jsonResponse(['success' => false, 'error' => 'Datos inválidos'], 400);
        }

        try {
            $ok = Carrera::desasociarMateria($this->conn, $idCarrera, $idMateria);
            if ($ok) {
                $this->jsonResponse(['success' => true, 'message' => 'Materia desasociada']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo desasociar'], 500);
            }
        } catch (Exception $e) {
            error_log('[CarreraController::desasociarMateria] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }
}

// Procesamiento de requests
if (basename($_SERVER['PHP_SELF']) === 'carreraController.php') {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    $controller = new CarreraController();

    switch ($action) {
        case 'listar':
            $controller->listar();
            break;
        case 'crear':
            $controller->crear();
            break;
        case 'actualizar':
            $controller->actualizar();
            break;
        case 'eliminar':
            $controller->eliminar();
            break;
        case 'asociar':
            $controller->asociarMateria();
            break;
        case 'desasociar':
            $controller->desasociarMateria();
            break;
        default:
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            break;
    }
}
