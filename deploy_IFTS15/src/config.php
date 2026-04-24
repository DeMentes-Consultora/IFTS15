<?php
/**
 * Configuración Central - IFTS15 con phpdotenv
 * Archivo de configuración centralizada que carga variables de entorno
 * 
 * @package App\Config
 * @author IFTS15 Team
 * @version 2.0
 */

// Importar las clases necesarias
use App\ConectionBD\ConectionDB;
use App\Database;

// Cargar phpdotenv solo una vez
if (!isset($_ENV['BASE_URL']) && !defined('DOTENV_LOADED')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/..');
    // Usar safeLoad para evitar excepción si no existe el archivo .env en desarrollo
    if (method_exists($dotenv, 'safeLoad')) {
        $dotenv->safeLoad();
    } else {
        // Retrocompatibilidad: intentar load() pero capturar errores
        try {
            $dotenv->load();
        } catch (Throwable $e) {
            // No hacemos fatal; continuamos con variables de entorno del sistema
        }
    }
    define('DOTENV_LOADED', true);
}

// Configuración de errores basada en variable de entorno
$debugMode = $_ENV['DEBUG_MODE'] ?? 'false';
if ($debugMode === 'true' || $debugMode === '1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function resolveBaseUrl(): string {
    $configuredBaseUrl = trim((string)($_ENV['BASE_URL'] ?? ''));
    $httpHost = trim((string)($_SERVER['HTTP_HOST'] ?? ''));
    $serverName = trim((string)($_SERVER['SERVER_NAME'] ?? ''));
    $host = $httpHost !== '' ? $httpHost : $serverName;

    if ($configuredBaseUrl !== '' && !($host !== '' && stripos($configuredBaseUrl, 'localhost') !== false && stripos($host, 'localhost') === false)) {
        return rtrim($configuredBaseUrl, '/');
    }

    if ($host !== '') {
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');

        return ($isHttps ? 'https://' : 'http://') . $host;
    }

    return 'http://localhost:8000';
}

// Definir BASE_URL desde variable de entorno
if (!defined('BASE_URL')) {
    $baseUrl = resolveBaseUrl();
    define('BASE_URL', $baseUrl);
}

// Alias para compatibilidad con código existente
if (!defined('SITE_URL')) {
    define('SITE_URL', BASE_URL);
}

// Configuraciones adicionales
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'IFTS15');
define('SITE_DESCRIPTION', $_ENV['SITE_DESCRIPTION'] ?? 'Instituto de Formación Técnica Superior N° 15');
define('SESSION_TIMEOUT', $_ENV['SESSION_TIMEOUT'] ?? 3600);
define('PASSWORD_MIN_LENGTH', $_ENV['PASSWORD_MIN_LENGTH'] ?? 6);

// Configuración adicional del sistema
define('DEBUG_MODE', $_ENV['DEBUG_MODE'] === 'true' || $_ENV['DEBUG_MODE'] === '1');

/**
 * Función helper para obtener variables de entorno con valor por defecto
 */
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

/**
 * Funciones helper para el sistema
 */

/**
 * Verificar si el usuario está logueado
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirigir a una página
 */
function redirect($url) {
    if (strpos($url, '/') === 0) {
        $url = SITE_URL . $url;
    }
    header("Location: $url");
    exit;
}

/**
 * Sanitizar entrada del usuario
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Mostrar mensaje de éxito
 */
function showSuccess($message) {
    $_SESSION['success_message'] = $message;
}

/**
 * Mostrar mensaje de error
 */
function showError($message) {
    $_SESSION['error_message'] = $message;
}

/**
 * Obtener y limpiar mensaje de éxito
 */
function getSuccessMessage() {
    if (isset($_SESSION['success_message'])) {
        $message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        return $message;
    }
    return null;
}

/**
 * Obtener y limpiar mensaje de error
 */
function getErrorMessage() {
    if (isset($_SESSION['error_message'])) {
        $message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
        return $message;
    }
    return null;
}

/**
 * Obtener información del usuario actual
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    // Verificar si tenemos datos del usuario en la sesión
    if (isset($_SESSION['user_data']) && is_array($_SESSION['user_data'])) {
        return $_SESSION['user_data'];
    }
    
    // Si no hay user_data pero sí user_id, devolver datos básicos
    if (isset($_SESSION['user_id'])) {
    $rid = $_SESSION['id_rol'] ?? $_SESSION['role_id'] ?? null;
    $roleNames = [1 => 'Alumno', 2 => 'Profesor', 3 => 'Administrativo', 4 => 'Directivo', 5 => 'Administrador'];
    return [
        'id' => $_SESSION['user_id'],
        'nombre_completo' => $_SESSION['nombre_completo'] ?? $_SESSION['nombre'] ?? 'Usuario',
        'nombre' => $_SESSION['nombre'] ?? 'Usuario',
        'apellido' => $_SESSION['apellido'] ?? '',
        'email' => $_SESSION['usuario'] ?? '',
        // Usar role_id numérico y nombre legible (según tabla roles)
        'role_id' => $rid,
        'role' => $roleNames[intval($rid)] ?? 'Alumno'
        ];
    }
    
    return null;
}

/**
 * Obtener id de rol del usuario actual (int|null)
 */
function getUserRoleId() {
    if (!isset($_SESSION)) return null;
    if (isset($_SESSION['id_rol'])) return intval($_SESSION['id_rol']);
    if (isset($_SESSION['role_id'])) return intval($_SESSION['role_id']);
    return null;
}

/**
 * Determinar si el usuario actual es administrador (roles administrativos)
 * Ajustar los ids según la tabla roles: 3=Administrativo, 4=Directivo, 5=Administrador
 */
function isAdminRole() {
    $rid = getUserRoleId();
    if ($rid === null) return false;
    return in_array($rid, [3,4,5], true);
}

/**
 * Determinar si el usuario puede acceder al dashboard de personalizacion del sitio
 * Roles permitidos: 3=Administrativo, 5=Administrador
 */
function canManageSiteCustomization() {
    $rid = getUserRoleId();
    if ($rid === null) return false;
    return in_array($rid, [3,5], true);
}