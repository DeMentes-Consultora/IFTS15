<?php
namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\ConectionBD\ConectionDB;
use App\Model\Materia;
use Exception;

require_once __DIR__ . '/../config.php';

class MateriaController
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
     * Listar materias (para la vista ABM)
     */
    public function listar()
    {
        // Verificar permisos admin
        if (!isLoggedIn() || !isAdminRole()) {
            header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
            exit;
        }

        $soloLibres = isset($_GET['libres']) && $_GET['libres'] === '1';

        try {
            $materias = Materia::obtenerTodas($this->conn, true, $soloLibres);
            
            // Si es petición AJAX, devolver JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                $this->jsonResponse(['success' => true, 'materias' => $materias]);
            }
            
            // Si no, devolver array para incluir en vista
            return $materias;
        } catch (Exception $e) {
            error_log('[MateriaController::listar] ' . $e->getMessage());
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
            }
            return [];
        }
    }

    /**
     * Crear materia (AJAX)
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
            $id = Materia::crear($this->conn, $nombre);
            if ($id) {
                $this->jsonResponse(['success' => true, 'id' => $id, 'message' => 'Materia creada exitosamente']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo crear la materia'], 500);
            }
        } catch (Exception $e) {
            error_log('[MateriaController::crear] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    /**
     * Actualizar materia (AJAX)
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
            $ok = Materia::actualizar($this->conn, $id, $nombre);
            if ($ok) {
                $this->jsonResponse(['success' => true, 'message' => 'Materia actualizada exitosamente']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo actualizar'], 500);
            }
        } catch (Exception $e) {
            error_log('[MateriaController::actualizar] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    /**
     * Eliminar materia (AJAX)
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
            $ok = Materia::eliminar($this->conn, $id);
            if ($ok) {
                $this->jsonResponse(['success' => true, 'message' => 'Materia eliminada exitosamente']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo eliminar'], 500);
            }
        } catch (Exception $e) {
            error_log('[MateriaController::eliminar] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }
}

// Procesamiento de requests
if (basename($_SERVER['PHP_SELF']) === 'materiaController.php') {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    $controller = new MateriaController();

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
        default:
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            break;
    }
}
