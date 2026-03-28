<?php
namespace App\Controllers;

// Controlador genérico para servir vistas desde /src/Views a través de una URL controlada
// Uso: viewController.php?view=nombre-de-la-vista (sin extensión)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';

// Sanitizar el nombre de la vista: permitir solo letras, numeros, guiones y subrayados
$view = $_GET['view'] ?? '';
$view = preg_replace('/[^a-zA-Z0-9_\-]/', '', $view);

if (empty($view)) {
    // Redirigir al index si no hay vista
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

// Allowlist: definir el nivel de acceso requerido para cada vista.
// Valores posibles: 'public' (cualquiera), 'guest' (solo no logueados), 'auth' (cualquier usuario logueado), 'admin' (solo administradores)
$allowlist = [
    'index' => 'public',
    'home' => 'public',
    'login' => 'guest',
    'register' => 'guest',
    'logout' => 'auth',
    'perfil' => 'auth',
    'reservas' => 'auth',
    'contacto' => 'public',
    'usuarios' => 'admin',
    'listadoEmpleados' => 'admin',
    'biblioteca' => 'auth',
    // Vista "Carreras" (nombre físico: realizador-productor-tv.php)
    'realizador-productor-tv' => 'public',
    'pagina_en_construccion' => 'public',
    // ABM Carreras y Materias
    'abm-carreras' => 'admin',
    // Añadir más vistas aquí según la app. Si una vista no está en la lista, denegamos el acceso por seguridad.
];

// Comprobar que la vista solicitada está en la allowlist
if (!array_key_exists($view, $allowlist)) {
    // Para evitar fugas de información, redirigimos al index con error genérico
    header('Location: ' . BASE_URL . '/index.php?error=accion_invalida');
    exit;
}

$required = $allowlist[$view];

// Helpers esperados en config.php: isLoggedIn() y isAdminRole(). Si no existen, definimos fallback conservador.
if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        return !empty($_SESSION['logged_in']);
    }
}
if (!function_exists('isAdminRole')) {
    function isAdminRole()
    {
        // Intentar detectar un campo de rol en sesión; ajustar según proyecto (id_rol, role, is_admin, etc.)
        if (!empty($_SESSION['id_rol'])) {
            return intval($_SESSION['id_rol']) === 1; // asumir 1 = admin
        }
        if (!empty($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin') {
            return true;
        }
        return false;
    }
}

$logged = isLoggedIn();
$isAdmin = isAdminRole();

switch ($required) {
    case 'public':
        // cualquiera puede acceder
        break;
    case 'guest':
        if ($logged) {
            // usuarios logueados no deberían acceder a páginas 'guest' (por ejemplo login/register)
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        break;
    case 'auth':
        if (!$logged) {
            // redirigir a login y llevar la ruta solicitada si se desea
            header('Location: ' . BASE_URL . '/login.php?next=' . urlencode($view));
            exit;
        }
        break;
    case 'admin':
        if (!$logged || !$isAdmin) {
            // acceso denegado
            header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
            exit;
        }
        break;
    default:
        header('Location: ' . BASE_URL . '/index.php?error=accion_invalida');
        exit;
}

// Buscar el archivo de la vista respetando mayúsculas/minúsculas: comprobamos case-insensitive
$viewsDir = __DIR__ . '/../Views/';
$requestedFile = $view . '.php';
$file = $viewsDir . $requestedFile;
if (!file_exists($file)) {
    // intentar búsqueda case-insensitive para evitar 404 en servidores Linux por diferencias de case
    $found = false;
    if (is_dir($viewsDir)) {
        $dh = opendir($viewsDir);
        if ($dh) {
            while (($f = readdir($dh)) !== false) {
                if (strtolower($f) === strtolower($requestedFile)) {
                    $file = $viewsDir . $f; // usar el nombre real
                    $found = true;
                    break;
                }
            }
            closedir($dh);
        }
    }
    if (!$found) {
        header('Location: ' . BASE_URL . '/index.php?error=accion_invalida');
        exit;
    }
}

// Incluir la vista a través del controlador (la vista puede requerir variables de sesión)
include $file;
exit;

?>
