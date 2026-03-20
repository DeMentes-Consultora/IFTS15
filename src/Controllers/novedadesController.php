<?php
// Controlador de novedades (MVC)
require_once __DIR__ . '/../Model/Novedades.php';
require_once __DIR__ . '/../ConectionBD/ConectionDB.php';

use App\ConectionBD\ConectionDB;
use App\Model\Novedades;


$db = new ConectionDB();
$conn = $db->getConnection();

/**
 * Helper para respuesta JSON unificada
 */
function jsonResponseNovedad(array $payload, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

// Alta de novedad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novedad'])) {
    $novedad = trim($_POST['novedad']);
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($novedad === '') {
        if ($isAjax) {
            jsonResponseNovedad(['success' => false, 'error' => 'Novedad vacía'], 400);
        } else {
            $_SESSION['novedades_message'] = 'Novedad vacía';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
            exit;
        }
    }

    try {
        Novedades::insertar($conn, $novedad);
        if ($isAjax) {
            jsonResponseNovedad(['success' => true, 'message' => 'Novedad guardada correctamente']);
        } else {
            $_SESSION['novedades_message'] = 'Novedad guardada correctamente';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
            exit;
        }
    } catch (Exception $e) {
        error_log('[novedadesController] Error al insertar novedad: ' . $e->getMessage());
        if ($isAjax) {
            jsonResponseNovedad(['success' => false, 'error' => 'No se pudo guardar la novedad. Intenta nuevamente.'], 500);
        } else {
            $_SESSION['novedades_message'] = 'No se pudo guardar la novedad. Intenta nuevamente.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
            exit;
        }
    }
}

// Listado de novedades
$novedades = Novedades::listar($conn);
