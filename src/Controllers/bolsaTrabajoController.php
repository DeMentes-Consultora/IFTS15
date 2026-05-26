<?php

namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\ConectionBD\ConectionDB;
use App\Model\BolsaTrabajo;
use App\Model\PostulacionBolsaTrabajo;
use App\Model\User;
use App\Services\CloudinaryService;
use App\Services\MailerService;
use Exception;
use InvalidArgumentException;
use Throwable;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/BolsaTrabajo.php';
require_once __DIR__ . '/../Model/PostulacionBolsaTrabajo.php';
require_once __DIR__ . '/../Model/User.php';
require_once __DIR__ . '/../services/CloudinaryService.php';
require_once __DIR__ . '/../services/MailerService.php';

class BolsaTrabajoController
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

    private function requireAuth(): void
    {
        if (!isLoggedIn()) {
            $this->jsonResponse(['success' => false, 'error' => 'No autenticado'], 401);
        }
    }

    private function requireBolsaAccess(): void
    {
        $this->requireAuth();
        if (!canAccessBolsaTrabajo()) {
            $this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
        }
    }

    private function requireBolsaManagement(): void
    {
        $this->requireAuth();
        if (!canManageBolsaTrabajo()) {
            $this->jsonResponse(['success' => false, 'error' => 'No tienes permisos para gestionar ofertas'], 403);
        }
    }

    private function requireAlumnoBolsa(): void
    {
        $this->requireAuth();
        if (getUserRoleId() !== 1) {
            $this->jsonResponse(['success' => false, 'error' => 'Solo los alumnos pueden postularse'], 403);
        }
    }

    private function uploadErrorText(int $code): string
    {
        switch ($code) {
            case UPLOAD_ERR_OK:
                return 'OK';
            case UPLOAD_ERR_INI_SIZE:
                return 'El archivo supera upload_max_filesize';
            case UPLOAD_ERR_FORM_SIZE:
                return 'El archivo supera MAX_FILE_SIZE del formulario';
            case UPLOAD_ERR_PARTIAL:
                return 'El archivo se subio parcialmente';
            case UPLOAD_ERR_NO_FILE:
                return 'No se envio archivo';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Falta carpeta temporal en servidor';
            case UPLOAD_ERR_CANT_WRITE:
                return 'No se pudo escribir el archivo temporal';
            case UPLOAD_ERR_EXTENSION:
                return 'Una extension de PHP bloqueo la subida';
            default:
                return 'Error de subida desconocido';
        }
    }

    private function currentUploadLimits(): array
    {
        return [
            'upload_max_filesize' => (string)ini_get('upload_max_filesize'),
            'post_max_size' => (string)ini_get('post_max_size'),
            'memory_limit' => (string)ini_get('memory_limit'),
            'sapi' => PHP_SAPI,
            'php_binary' => PHP_BINARY,
        ];
    }

    private function inferCvFileName(?string $preferredName, ?string $publicId = null, ?string $cvUrl = null): string
    {
        $candidates = [$preferredName, $publicId, $cvUrl];

        foreach ($candidates as $candidate) {
            $candidate = trim((string)$candidate);
            if ($candidate === '') {
                continue;
            }

            $path = parse_url($candidate, PHP_URL_PATH);
            $name = basename((string)($path ?: $candidate));
            if ($name !== '' && $name !== '.' && $name !== '..') {
                return $name;
            }
        }

        return 'cv';
    }

    private function buildCvDownloadUrl(?string $publicId, ?string $fileName = null, ?string $fallbackUrl = null): ?string
    {
        if (is_string($fallbackUrl) && $fallbackUrl !== '' && strpos($fallbackUrl, '/upload/') !== false) {
            $cleanUrl = preg_replace('#/fl_attachment(?::[^/]+)?/#', '/', $fallbackUrl);
            return preg_replace('#/upload/#', '/upload/fl_attachment/', $cleanUrl, 1) ?: $fallbackUrl;
        }

        if (!is_string($publicId) || $publicId === '') {
            return $fallbackUrl;
        }

        $cloudName = trim((string)($_ENV['CLOUDINARY_CLOUD_NAME'] ?? ''));
        if ($cloudName === '') {
            return $fallbackUrl;
        }

        return 'https://res.cloudinary.com/' . rawurlencode($cloudName) . '/raw/upload/fl_attachment/' . ltrim($publicId, '/');
    }

    private function addCvDownloadUrls(array $rows, ?CloudinaryService $cloudinary = null): array
    {
        if ($rows === []) {
            return $rows;
        }

        foreach ($rows as &$row) {
            $fileName = $this->inferCvFileName(null, $row['cv_public_id'] ?? null, $row['cv_url'] ?? null);
            $row['cv_download_url'] = $this->buildCvDownloadUrl(
                $row['cv_public_id'] ?? null,
                $fileName,
                $row['cv_url'] ?? null
            );
        }
        unset($row);

        return $rows;
    }

    private function deleteCvFromCloudinary(?string $cvPublicId): void
    {
        $cvPublicId = trim((string)$cvPublicId);
        if ($cvPublicId === '') {
            return;
        }

        try {
            $cloudinary = new CloudinaryService();
            $cloudinary->deleteImage($cvPublicId, 'raw');
        } catch (Throwable $e) {
            error_log('[BolsaTrabajoController::deleteCvFromCloudinary] ' . $e->getMessage());
        }
    }

    private function obtenerPostulacionPorIdYUsuario(int $idPostulacion, int $idUsuario): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT id_postulacion_bolsa_trabajo, cancelado, cv_url, cv_public_id
             FROM postulacion_bolsa_trabajo
             WHERE id_postulacion_bolsa_trabajo = ? AND id_usuario = ?
             LIMIT 1'
        );
        $stmt->bind_param('ii', $idPostulacion, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    private function actualizarCvPostulacionEnBd(int $idPostulacion, int $idUsuario, string $cvUrl, string $cvPublicId): bool
    {
        $stmt = $this->conn->prepare(
            'UPDATE postulacion_bolsa_trabajo
             SET cv_url = ?, cv_public_id = ?
             WHERE id_postulacion_bolsa_trabajo = ? AND id_usuario = ? AND cancelado = 0'
        );
        $stmt->bind_param('ssii', $cvUrl, $cvPublicId, $idPostulacion, $idUsuario);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    private function validarCV(array $archivo): array
    {
        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            throw new InvalidArgumentException('El CV es obligatorio para postularse');
        }

        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $limits = $this->currentUploadLimits();
            throw new InvalidArgumentException(
                'Error al recibir el archivo: ' .
                $this->uploadErrorText((int)$archivo['error']) .
                '. Limites activos del servidor: upload_max_filesize=' . $limits['upload_max_filesize'] .
                ', post_max_size=' . $limits['post_max_size'] .
                ', sapi=' . $limits['sapi']
            );
        }

        $fileTmpPath = (string)($archivo['tmp_name'] ?? '');
        $fileName = (string)($archivo['name'] ?? '');
        $fileSize = (int)($archivo['size'] ?? 0);
        $extension = strtolower((string)pathinfo($fileName, PATHINFO_EXTENSION));

        if (!is_uploaded_file($fileTmpPath)) {
            throw new InvalidArgumentException('No se pudo validar el CV subido');
        }

        $extensionesPermitidas = ['pdf', 'doc', 'docx'];
        if (!in_array($extension, $extensionesPermitidas, true)) {
            throw new InvalidArgumentException('El CV debe ser un archivo PDF, DOC o DOCX');
        }

        $mimeType = '';
        if (function_exists('finfo_open') && defined('FILEINFO_MIME_TYPE')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mimeType = (string)finfo_file($finfo, $fileTmpPath);
                finfo_close($finfo);
            }
        }

        $tiposPermitidos = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/octet-stream',
        ];

        if ($mimeType !== '' && !in_array($mimeType, $tiposPermitidos, true)) {
            throw new InvalidArgumentException('El CV debe ser un archivo PDF, DOC o DOCX');
        }

        if ($fileSize > (5 * 1024 * 1024)) {
            throw new InvalidArgumentException('El CV no puede superar los 5 MB');
        }

        return [
            'tmp_name' => $fileTmpPath,
            'name' => $fileName,
        ];
    }

    public function listarPublicadas(): void
    {
        $this->requireBolsaAccess();

        try {
            $ofertas = BolsaTrabajo::obtenerPublicadas($this->conn);

            if (getUserRoleId() === 1) {
                $idUsuario = (int)($_SESSION['user_id'] ?? 0);
                $postulacionesActivas = PostulacionBolsaTrabajo::obtenerMapaActivasPorUsuario($this->conn, $idUsuario);

                foreach ($ofertas as &$oferta) {
                    $idOferta = (int)($oferta['id_bolsa_trabajo'] ?? 0);
                    $postulacion = $postulacionesActivas[$idOferta] ?? null;
                    $oferta['ya_postulado'] = $postulacion !== null;
                    $oferta['id_postulacion_bolsa_trabajo'] = $postulacion['id_postulacion_bolsa_trabajo'] ?? null;
                }
                unset($oferta);
            }

            $this->jsonResponse(['success' => true, 'ofertas' => $ofertas]);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::listarPublicadas] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function listarPendientes(): void
    {
        $this->requireBolsaManagement();

        try {
            $ofertas = BolsaTrabajo::obtenerOfertasGestion($this->conn);
            $this->jsonResponse(['success' => true, 'ofertas' => $ofertas]);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::listarPendientes] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function listarPostulacionesGestion(): void
    {
        $this->requireBolsaManagement();

        try {
            $postulaciones = PostulacionBolsaTrabajo::obtenerGestionGlobal($this->conn);
            $postulaciones = $this->addCvDownloadUrls($postulaciones);
            $this->jsonResponse(['success' => true, 'postulaciones' => $postulaciones]);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::listarPostulacionesGestion] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function resumen(): void
    {
        $this->requireBolsaManagement();

        try {
            $resumen = BolsaTrabajo::obtenerResumen($this->conn);
            $this->jsonResponse(['success' => true, 'resumen' => $resumen]);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::resumen] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Metodo no permitido'], 405);
        }

        $this->requireBolsaManagement();

        $titulo = trim((string)($_POST['titulo'] ?? ''));
        $texto = trim((string)($_POST['texto'] ?? ''));
        $idUsuario = (int)($_SESSION['user_id'] ?? 0);

        if ($idUsuario <= 0 || $titulo === '' || $texto === '') {
            $this->jsonResponse(['success' => false, 'error' => 'Titulo y descripcion son obligatorios'], 400);
        }

        try {
            $idOferta = BolsaTrabajo::crearOferta($this->conn, $idUsuario, $titulo, $texto);
            if (!$idOferta) {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo crear la oferta'], 500);
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'Oferta publicada correctamente',
                'id' => $idOferta,
            ]);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::crear] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function gestionar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Metodo no permitido'], 405);
        }

        $this->requireBolsaManagement();

        $idOferta = (int)($_POST['id_bolsa_trabajo'] ?? 0);
        $accion = trim((string)($_POST['accion'] ?? ''));

        if ($idOferta <= 0 || !in_array($accion, ['activar', 'desactivar', 'eliminar'], true)) {
            $this->jsonResponse(['success' => false, 'error' => 'Datos invalidos'], 400);
        }

        try {
            $oferta = BolsaTrabajo::obtenerPorId($this->conn, $idOferta);
            if (!$oferta) {
                $this->jsonResponse(['success' => false, 'error' => 'Oferta no encontrada'], 404);
            }

            $ok = false;
            $message = 'Accion no valida';

            switch ($accion) {
                case 'activar':
                    $ok = BolsaTrabajo::activarOferta($this->conn, $idOferta);
                    $message = 'Oferta activada correctamente';
                    break;
                case 'desactivar':
                    $ok = BolsaTrabajo::deshabilitarOferta($this->conn, $idOferta);
                    $message = 'Oferta desactivada';
                    break;
                case 'eliminar':
                    $ok = BolsaTrabajo::eliminarOferta($this->conn, $idOferta);
                    $message = 'Oferta ocultada del sistema';
                    break;
            }

            if (!$ok) {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo actualizar la oferta'], 500);
            }

            $this->jsonResponse(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::gestionar] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function listarMisPostulaciones(): void
    {
        $this->requireAlumnoBolsa();

        try {
            $idUsuario = (int)($_SESSION['user_id'] ?? 0);
            $postulaciones = PostulacionBolsaTrabajo::obtenerActivasDeAlumno($this->conn, $idUsuario);
            $postulaciones = $this->addCvDownloadUrls($postulaciones);
            $this->jsonResponse(['success' => true, 'postulaciones' => $postulaciones]);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::listarMisPostulaciones] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function postularse(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Metodo no permitido'], 405);
        }

        $this->requireAlumnoBolsa();

        $idOferta = (int)($_POST['id_bolsa_trabajo'] ?? 0);
        $idUsuario = (int)($_SESSION['user_id'] ?? 0);

        if ($idOferta <= 0 || $idUsuario <= 0) {
            $this->jsonResponse(['success' => false, 'error' => 'Datos invalidos'], 400);
        }

        $transactionStarted = false;
        $cvPublicIdAnteriorAEliminar = null;

        try {
            $oferta = BolsaTrabajo::obtenerPorId($this->conn, $idOferta);
            if (!$oferta || (int)$oferta['habilitado'] !== 1 || (int)$oferta['cancelado'] !== 0) {
                $this->jsonResponse(['success' => false, 'error' => 'La oferta no existe o no esta disponible'], 404);
            }

            $archivo = $this->validarCV($_FILES['cv'] ?? []);
            $existente = PostulacionBolsaTrabajo::obtenerPorOfertaYUsuario($this->conn, $idOferta, $idUsuario);

            if ($existente && (int)$existente['cancelado'] === 0) {
                $this->jsonResponse(['success' => false, 'error' => 'Ya estas postulado a esta oferta'], 409);
            }

            $cloudinary = new CloudinaryService();
            $upload = $cloudinary->uploadRawFile($archivo['tmp_name'], $archivo['name'], 'ifts15/cv');
            $cvPublicId = $upload['public_id'] ?? null;
            $cvUrl = $upload['secure_url'] ?? $upload['url'] ?? null;

            if (!$cvUrl || !$cvPublicId) {
                throw new Exception('No se pudo subir el CV');
            }

            $this->conn->begin_transaction();
            $transactionStarted = true;

            if ($existente && (int)$existente['cancelado'] === 1) {
                $cvPublicIdAnteriorAEliminar = $existente['cv_public_id'] ?? null;
                $ok = PostulacionBolsaTrabajo::reactivarPostulacion(
                    $this->conn,
                    (int)$existente['id_postulacion_bolsa_trabajo'],
                    $cvUrl,
                    $cvPublicId
                );

                $idPostulacion = $ok ? (int)$existente['id_postulacion_bolsa_trabajo'] : null;
            } else {
                $idPostulacion = PostulacionBolsaTrabajo::crearPostulacion(
                    $this->conn,
                    $idOferta,
                    $idUsuario,
                    $cvUrl,
                    $cvPublicId
                );
            }

            if (!$idPostulacion) {
                $this->conn->rollback();
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo guardar la postulacion'], 500);
            }

            $this->conn->commit();
            $transactionStarted = false;

            if (
                $cvPublicIdAnteriorAEliminar !== null &&
                $cvPublicIdAnteriorAEliminar !== '' &&
                $cvPublicIdAnteriorAEliminar !== $cvPublicId
            ) {
                $this->deleteCvFromCloudinary($cvPublicIdAnteriorAEliminar);
            }

            $mailAlumnoEnviado = false;
            $mailAlumnoError = null;

            try {
                $usuarioAlumno = User::obtenerUsuarioCompleto($this->conn, $idUsuario);
                $emailAlumno = trim((string)($usuarioAlumno['email'] ?? ''));
                $nombreAlumno = trim((string)($usuarioAlumno['nombre'] ?? '') . ' ' . (string)($usuarioAlumno['apellido'] ?? ''));
                $nombrePublicador = trim((string)($oferta['nombre'] ?? '') . ' ' . (string)($oferta['apellido'] ?? ''));

                if ($emailAlumno !== '') {
                    $mailer = new MailerService();
                    $nombreAlumnoSeguro = htmlspecialchars($nombreAlumno !== '' ? $nombreAlumno : 'Alumno', ENT_QUOTES, 'UTF-8');
                    $tituloOfertaSeguro = htmlspecialchars((string)($oferta['titulo_oferta'] ?? 'Oferta laboral'), ENT_QUOTES, 'UTF-8');
                    $publicadorSeguro = htmlspecialchars($nombrePublicador !== '' ? $nombrePublicador : 'IFTS15', ENT_QUOTES, 'UTF-8');
                    $mailBody = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.08); overflow: hidden; }
        .header { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: #ffffff; padding: 28px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 28px; color: #333333; }
        .box { background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 16px; margin: 20px 0; }
        .box p { margin: 6px 0; }
        .footer { background: #f8f9fa; padding: 18px; text-align: center; font-size: 12px; color: #666666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Postulacion recibida</h1>
        </div>
        <div class="body">
            <p>Hola <strong>{$nombreAlumnoSeguro}</strong>,</p>
            <p>Recibimos correctamente tu postulacion a la oferta laboral indicada a continuacion.</p>
            <div class="box">
                <p><strong>Oferta:</strong> {$tituloOfertaSeguro}</p>
                <p><strong>Publicada por:</strong> {$publicadorSeguro}</p>
                <p><strong>Estado:</strong> Postulacion recibida</p>
            </div>
            <p>Si tu postulacion avanza o hay novedades sobre este proceso, te avisaremos por este mismo medio.</p>
            <p>Saludos,<br>Equipo de IFTS15</p>
        </div>
        <div class="footer">
            Este es un correo automatico de IFTS15. No respondas este mensaje.
        </div>
    </div>
</body>
</html>
HTML;
                    $mailResult = $mailer->send($emailAlumno, 'Postulacion recibida - IFTS15', $mailBody, true, null);

                    $mailAlumnoEnviado = (bool)($mailResult['success'] ?? false);
                    if (!$mailAlumnoEnviado) {
                        $mailAlumnoError = (string)($mailResult['message'] ?? 'Error sin detalle');
                        error_log('[BolsaTrabajoController::postularse] Error mail alumno: ' . $mailAlumnoError);
                    }
                } else {
                    $mailAlumnoError = 'El alumno no tiene email disponible para notificacion';
                    error_log('[BolsaTrabajoController::postularse] ' . $mailAlumnoError);
                }
            } catch (Throwable $mailException) {
                $mailAlumnoError = $mailException->getMessage();
                error_log('[BolsaTrabajoController::postularse] Error mail alumno: ' . $mailAlumnoError);
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'Te postulaste correctamente a la oferta. Recibiras un correo de confirmacion.',
                'id_postulacion_bolsa_trabajo' => $idPostulacion,
                'mail' => [
                    'alumno_enviado' => $mailAlumnoEnviado,
                    'alumno_error' => $mailAlumnoError,
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            if ($transactionStarted) {
                $this->conn->rollback();
            }
            error_log('[BolsaTrabajoController::postularse] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function actualizarCvPostulacion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Metodo no permitido'], 405);
        }

        $this->requireAlumnoBolsa();

        $idPostulacion = (int)($_POST['id_postulacion_bolsa_trabajo'] ?? 0);
        $idUsuario = (int)($_SESSION['user_id'] ?? 0);

        if ($idPostulacion <= 0 || $idUsuario <= 0) {
            $this->jsonResponse(['success' => false, 'error' => 'Datos invalidos'], 400);
        }

        $transactionStarted = false;

        try {
            $postulacion = $this->obtenerPostulacionPorIdYUsuario($idPostulacion, $idUsuario);
            if (!$postulacion || (int)($postulacion['cancelado'] ?? 1) !== 0) {
                $this->jsonResponse(['success' => false, 'error' => 'Solo podes actualizar una postulacion activa'], 404);
            }

            $archivo = $this->validarCV($_FILES['cv'] ?? []);

            $cloudinary = new CloudinaryService();
            $upload = $cloudinary->uploadRawFile($archivo['tmp_name'], $archivo['name'], 'ifts15/cv');
            $cvPublicId = $upload['public_id'] ?? null;
            $cvUrl = $upload['secure_url'] ?? $upload['url'] ?? null;

            if (!$cvUrl || !$cvPublicId) {
                throw new Exception('No se pudo subir el CV');
            }

            $this->conn->begin_transaction();
            $transactionStarted = true;

            $ok = $this->actualizarCvPostulacionEnBd($idPostulacion, $idUsuario, $cvUrl, $cvPublicId);

            if (!$ok) {
                $this->conn->rollback();
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo actualizar el CV de la postulacion'], 500);
            }

            $this->conn->commit();
            $transactionStarted = false;

            $cvPublicIdAnterior = $postulacion['cv_public_id'] ?? null;
            if (
                $cvPublicIdAnterior !== null &&
                $cvPublicIdAnterior !== '' &&
                $cvPublicIdAnterior !== $cvPublicId
            ) {
                $this->deleteCvFromCloudinary($cvPublicIdAnterior);
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'CV actualizado correctamente',
                'cv_url' => $cvUrl,
            ]);
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            if ($transactionStarted) {
                $this->conn->rollback();
            }
            error_log('[BolsaTrabajoController::actualizarCvPostulacion] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }

    public function cancelarPostulacion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Metodo no permitido'], 405);
        }

        $this->requireAlumnoBolsa();

        $idPostulacion = (int)($_POST['id_postulacion_bolsa_trabajo'] ?? 0);
        $idUsuario = (int)($_SESSION['user_id'] ?? 0);

        if ($idPostulacion <= 0 || $idUsuario <= 0) {
            $this->jsonResponse(['success' => false, 'error' => 'Datos invalidos'], 400);
        }

        try {
            $postulacion = $this->obtenerPostulacionPorIdYUsuario($idPostulacion, $idUsuario);
            if (!$postulacion || (int)($postulacion['cancelado'] ?? 1) !== 0) {
                $this->jsonResponse(['success' => false, 'error' => 'No se encontro una postulacion activa para cancelar'], 404);
            }

            $ok = PostulacionBolsaTrabajo::cancelarDeAlumno($this->conn, $idPostulacion, $idUsuario);
            if (!$ok) {
                $this->jsonResponse(['success' => false, 'error' => 'No se pudo cancelar la postulacion'], 400);
            }

            $this->deleteCvFromCloudinary($postulacion['cv_public_id'] ?? null);

            $this->jsonResponse(['success' => true, 'message' => 'Postulacion cancelada']);
        } catch (Exception $e) {
            error_log('[BolsaTrabajoController::cancelarPostulacion] ' . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
        }
    }
}

if (basename($_SERVER['PHP_SELF']) === 'bolsaTrabajoController.php') {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    $controller = new BolsaTrabajoController();

    switch ($action) {
        case 'listar-publicadas':
            $controller->listarPublicadas();
            break;
        case 'listar-pendientes':
            $controller->listarPendientes();
            break;
        case 'listar-postulaciones-gestion':
            $controller->listarPostulacionesGestion();
            break;
        case 'resumen':
            $controller->resumen();
            break;
        case 'crear':
            $controller->crear();
            break;
        case 'gestionar':
            $controller->gestionar();
            break;
        case 'listar-mis-postulaciones':
            $controller->listarMisPostulaciones();
            break;
        case 'postularse':
            $controller->postularse();
            break;
        case 'actualizar-cv-postulacion':
            $controller->actualizarCvPostulacion();
            break;
        case 'cancelar-postulacion':
            $controller->cancelarPostulacion();
            break;
        default:
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'error' => 'Accion no valida']);
            break;
    }
}