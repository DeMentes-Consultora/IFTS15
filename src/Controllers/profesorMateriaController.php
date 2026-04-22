<?php

namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\ConectionBD\ConectionDB;
use App\Model\ProfesorMateria;

require_once __DIR__ . '/../config.php';

class ProfesorMateriaController
{
    private $conn;

    public function __construct()
    {
        $db = new ConectionDB();
        $this->conn = $db->getConnection();
    }

    private function jsonResponse(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }

    private function requireAdminOrAdministrativo(): void
    {
        if (!isLoggedIn()) {
            $this->jsonResponse(['success' => false, 'error' => 'No autenticado'], 401);
        }

        $idRol = (int)($_SESSION['id_rol'] ?? $_SESSION['role_id'] ?? 0);
        if (!in_array($idRol, [3, 5], true)) {
            $this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
        }
    }

    public function listar()
    {
        $this->requireAdminOrAdministrativo();
        ProfesorMateria::asegurarTabla($this->conn);

        $idProfesor = (int)($_GET['id_profesor'] ?? 0);
        $idCarrera = (int)($_GET['id_carrera'] ?? 0);
        $idMateria = (int)($_GET['id_materia'] ?? 0);

        $tabla = [];
        if ($idProfesor > 0) {
            $tabla = ProfesorMateria::obtenerTablaAsignacion($this->conn, $idProfesor, $idCarrera, $idMateria);
        }

        $this->jsonResponse([
            'success' => true,
            'profesores' => ProfesorMateria::obtenerProfesores($this->conn),
            'carreras' => ProfesorMateria::obtenerCarreras($this->conn),
            'tabla' => $tabla,
        ]);
    }

    public function toggle()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
        }

        $this->requireAdminOrAdministrativo();
        ProfesorMateria::asegurarTabla($this->conn);

        $idProfesor = (int)($_POST['id_profesor'] ?? 0);
        $idMateria = (int)($_POST['id_materia'] ?? 0);
        $asignada = ((int)($_POST['asignada'] ?? 0) === 1);

        if ($idProfesor <= 0 || $idMateria <= 0) {
            $this->jsonResponse(['success' => false, 'error' => 'Parámetros inválidos'], 400);
        }

        if (!ProfesorMateria::esProfesorValido($this->conn, $idProfesor)) {
            $this->jsonResponse(['success' => false, 'error' => 'Profesor no válido'], 400);
        }

        if (!ProfesorMateria::esMateriaValida($this->conn, $idMateria)) {
            $this->jsonResponse(['success' => false, 'error' => 'Materia no válida'], 400);
        }

        $ok = ProfesorMateria::toggleAsignacion($this->conn, $idProfesor, $idMateria, $asignada);
        if (!$ok) {
            $this->jsonResponse(['success' => false, 'error' => 'No se pudo actualizar la asignación'], 500);
        }

        $this->jsonResponse([
            'success' => true,
            'message' => $asignada ? 'Materia asignada al profesor' : 'Materia removida del profesor',
        ]);
    }
}

if (basename($_SERVER['PHP_SELF']) === 'profesorMateriaController.php') {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    $controller = new ProfesorMateriaController();

    switch ($action) {
        case 'listar':
            $controller->listar();
            break;
        case 'toggle':
            $controller->toggle();
            break;
        default:
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            break;
    }
}
