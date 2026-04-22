<?php
namespace App\Controllers;

// Iniciar sesión antes de cualquier output
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

use App\ConectionBD\ConectionDB;
use App\Model\User;
use App\Services\MailerService;
use Exception;

// Controlador simple para listar usuarios y cambiar habilitado
class usuarioController
{
	private $conn;
	private $db;

	public function __construct()
	{
		if (!function_exists('env')) {
			require_once __DIR__ . '/../config.php';
		}
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
	 * Listar usuarios con paginación y mostrar la vista
	 */
	public function listar()
	{
		// Solo roles administrativos pueden ver esta pantalla (3,4,5)
		if (!function_exists('isAdminRole')) {
			require_once __DIR__ . '/../config.php';
		}
		if (!isLoggedIn() || !isAdminRole()) {
			header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
			exit;
		}

		$page = max(1, intval($_GET['page'] ?? 1));
		$limit = intval($_GET['limit'] ?? 10);
		if ($limit <= 0) $limit = 10;
		$offset = ($page - 1) * $limit;

		try {
			$total = User::contarTodos($this->conn);
			$usuarios = User::obtenerTodos($this->conn, $limit, $offset);
		} catch (Exception $e) {
			error_log('Error al obtener usuarios: ' . $e->getMessage());
			$usuarios = [];
			$total = 0;
		}

		// Incluir la vista (solo vista)
		include __DIR__ . '/../Views/usuarios.php';
	}

	/**
	 * Toggle habilitado via AJAX
	 */
	public function toggleHabilitado()
	{
		// Permitir solo POST
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
		}

		// Verificar permisos (roles administrativos)
		if (!function_exists('isAdminRole')) {
			require_once __DIR__ . '/../config.php';
		}
		if (!isLoggedIn() || !isAdminRole()) {
			$this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
		}

		// Validar token CSRF
		$postedToken = $_POST['csrf_token'] ?? '';
		if (empty($postedToken) || empty($_SESSION['csrf_usuario_toggle']) || !hash_equals($_SESSION['csrf_usuario_toggle'], $postedToken)) {
			$this->jsonResponse(['success' => false, 'error' => 'Token CSRF inválido'], 403);
		}

		$id = intval($_POST['id'] ?? 0);
		$habilitado = intval($_POST['habilitado'] ?? 0);

		if ($id <= 0) {
			$this->jsonResponse(['success' => false, 'error' => 'ID inválido'], 400);
		}

		try {
			$ok = User::actualizarHabilitado($this->conn, $id, $habilitado);
			if ($ok) {
				// Rotar el token CSRF y devolver el nuevo token en la respuesta
				try {
					$newToken = bin2hex(random_bytes(32));
				} catch (Exception $e) {
					$newToken = bin2hex(openssl_random_pseudo_bytes(32));
				}
				$_SESSION['csrf_usuario_toggle'] = $newToken;
					// Si se habilitó al usuario, notificar por mail
					if ($habilitado == 1) {
						try {
							// obtener datos del usuario para notificar
							$datosUsuario = User::obtenerUsuarioCompleto($this->conn, $id);
							if ($datosUsuario && !empty($datosUsuario['email'])) {
								// incluir utilidad de envio de mails
								$to = $datosUsuario['email'];
								$nombre = trim(($datosUsuario['nombre'] ?? '') . ' ' . ($datosUsuario['apellido'] ?? ''));
								$subject = 'Tu cuenta en IFTS15 ha sido habilitada';
								$body = '<p>Hola ' . htmlspecialchars($nombre) . ',</p>';
								$body .= '<p>Tu cuenta en el campus IFTS15 ha sido habilitada. Ya podés iniciar sesión con tu correo y contraseña.</p>';
								$body .= '<p>Si no reconocés esta acción, contactá con el área de soporte.</p>';
								$body .= '<p>Saludos,<br>Equipo IFTS15</p>';
								$mailer = new MailerService();
								$resMail = $mailer->send($to, $subject, $body, true, $to);
								if (!$resMail['success']) {
									error_log('[usuarioController::toggleHabilitado] Error enviando mail de habilitación: ' . ($resMail['message'] ?? 'sin detalle'));
								}
							}
						} catch (Exception $e) {
							error_log('[usuarioController::toggleHabilitado] Excepción al notificar habilitación por mail: ' . $e->getMessage());
						}
					}
				$this->jsonResponse(['success' => true, 'new_csrf' => $newToken]);
			} else {
				$this->jsonResponse(['success' => false, 'error' => 'No se pudo actualizar'], 500);
			}
		} catch (Exception $e) {
			error_log('[usuarioController::toggleHabilitado] ' . $e->getMessage());
			$this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
		}
	}

	/**
	 * Habilitar múltiples usuarios en lote via AJAX
	 */
	public function habilitarLote()
	{
		// Permitir solo POST
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->jsonResponse(['success' => false, 'error' => 'Método no permitido'], 405);
		}

		// Verificar permisos (roles administrativos)
		if (!function_exists('isAdminRole')) {
			require_once __DIR__ . '/../config.php';
		}
		if (!isLoggedIn() || !isAdminRole()) {
			$this->jsonResponse(['success' => false, 'error' => 'Acceso denegado'], 403);
		}

		// Leer JSON del body
		$input = json_decode(file_get_contents('php://input'), true);
		$ids = $input['ids'] ?? [];

		if (!is_array($ids) || empty($ids)) {
			$this->jsonResponse(['success' => false, 'error' => 'IDs inválidos o vacíos'], 400);
		}

		// Sanitizar IDs
		$ids = array_map('intval', $ids);
		$ids = array_filter($ids, function($id) { return $id > 0; });

		if (empty($ids)) {
			$this->jsonResponse(['success' => false, 'error' => 'No hay IDs válidos'], 400);
		}

		$habilitados = 0;
		$errores = [];

		try {
			foreach ($ids as $id) {
				try {
					$ok = User::actualizarHabilitado($this->conn, $id, 1);
					if ($ok) {
						$habilitados++;
						// Enviar mail de habilitación
						try {
							$datosUsuario = User::obtenerUsuarioCompleto($this->conn, $id);
							if ($datosUsuario && !empty($datosUsuario['email'])) {
								$to = $datosUsuario['email'];
								$nombre = trim(($datosUsuario['nombre'] ?? '') . ' ' . ($datosUsuario['apellido'] ?? ''));
								$subject = 'Tu cuenta en IFTS15 ha sido habilitada';
								$body = '<p>Hola ' . htmlspecialchars($nombre) . ',</p>';
								$body .= '<p>Tu cuenta en el campus IFTS15 ha sido habilitada. Ya podés iniciar sesión con tu correo y contraseña.</p>';
								$body .= '<p>Si no reconocés esta acción, contactá con el área de soporte.</p>';
								$body .= '<p>Saludos,<br>Equipo IFTS15</p>';
								$mailer = new MailerService();
								$resMail = $mailer->send($to, $subject, $body, true, $to);
								if (!$resMail['success']) {
									error_log('[usuarioController::habilitarLote] Error enviando mail a ' . $to . ': ' . ($resMail['message'] ?? 'sin detalle'));
								}
							}
						} catch (Exception $e) {
							error_log('[usuarioController::habilitarLote] Excepción al notificar habilitación por mail para usuario ' . $id . ': ' . $e->getMessage());
						}
					} else {
						$errores[] = 'Usuario ' . $id . ' no se pudo actualizar';
					}
				} catch (Exception $e) {
					error_log('[usuarioController::habilitarLote] Error habilitando usuario ' . $id . ': ' . $e->getMessage());
					$errores[] = 'Usuario ' . $id . ': ' . $e->getMessage();
				}
			}

			$this->jsonResponse([
				'success' => true,
				'habilitados' => $habilitados,
				'total' => count($ids),
				'errores' => $errores,
				'mensaje' => $habilitados . ' usuario(s) habilitado(s) correctamente.'
			]);
		} catch (Exception $e) {
			error_log('[usuarioController::habilitarLote] ' . $e->getMessage());
			$this->jsonResponse(['success' => false, 'error' => 'Error interno'], 500);
		}
	}
}

// Procesamiento de requests cuando se accede directamente al archivo
if (basename($_SERVER['PHP_SELF']) === 'usuarioController.php') {
	$action = $_GET['action'] ?? $_POST['action'] ?? '';
	$controller = new usuarioController();

	switch ($action) {
		case 'listar':
			$controller->listar();
			break;
		case 'toggle':
			$controller->toggleHabilitado();
			break;
		case 'habilitar_lote':
			$controller->habilitarLote();
			break;
		default:
			// Acción por defecto: listar
			$controller->listar();
			break;
	}
}

?>

