<?php
/**
 * Logout - Cerrar Sesión
 * Migrado a phpdotenv
 */

// Cargar configuración central con phpdotenv
require_once __DIR__ . '/src/config.php';

// Verificar que el usuario esté logueado
if (!isLoggedIn()) {
    redirect('/index.php');
}

// Obtener nombre para mensaje de despedida
$currentUser = getCurrentUser();
$nombre = 'Usuario'; // Valor por defecto

if ($currentUser && is_array($currentUser)) {
    $nombre = $currentUser['nombre'] ?? $currentUser['email'] ?? 'Usuario';
}

// Cerrar sesión completamente
session_start();
session_unset();
session_destroy();

// Iniciar nueva sesión para el mensaje
session_start();

// Redirigir con mensaje de despedida
showSuccess("¡Hasta pronto, $nombre! Has cerrado sesión correctamente.");
redirect('/index.php');
?>
