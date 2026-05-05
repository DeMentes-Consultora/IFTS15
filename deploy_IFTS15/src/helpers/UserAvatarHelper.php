<?php
// helpers/UserAvatarHelper.php
// Devuelve la URL de la foto de perfil del usuario logueado, siempre actualizada desde la BD

use App\Model\User;

function getUserAvatarUrl($conn) {
    if (!$conn instanceof mysqli) return null;
    $userId = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : (isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0);
    if ($userId <= 0) return null;
    $datos = User::obtenerUsuarioCompleto($conn, $userId);
    return $datos['foto_perfil_url'] ?? null;
}
