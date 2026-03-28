<?php
/**
 * Vista: Gestión de Usuarios
 * 
 * Muestra una tabla con todos los usuarios del sistema
 * permitiendo a los administradores gestionar sus datos
 * 
 * Variables esperadas:
 * - $usuarios: Array con la lista de usuarios
 * - $page: Página actual de la paginación
 * - $limit: Cantidad de registros por página
 * - $total: Total de registros
 * 
 * @package App\Views
 */

// Asegurar sesión y variables esperadas por las plantillas
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Obtener información del usuario actual
if (!function_exists('getCurrentUser')) {
	require_once __DIR__ . '/../config.php';
}

$currentUser = getCurrentUser();
$isLoggedIn = $currentUser !== null;
$userEmail = $currentUser['email'] ?? $_SESSION['email'] ?? $_SESSION['usuario'] ?? '';
$userRole = $currentUser['role'] ?? ($_SESSION['role_name'] ?? $_SESSION['user_role'] ?? ($_SESSION['id_rol'] ?? 'Usuario'));

require_once __DIR__ . '/../Template/head.php';
require_once __DIR__ . '/../Template/navBar.php';

// Sidebar si está logueado
if ($isLoggedIn) {
	include __DIR__ . '/../Template/sidebar.php';
}
?>

<!-- CSS específico para la vista de usuarios -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/usuariosCss.css">

<main class="flex-fill">
	<div class="container py-4">
		<?php include __DIR__ . '/../Components/tablaUsuarios.php'; ?>
	</div>
</main>

<?php include __DIR__ . '/../Template/footer.php'; ?>

