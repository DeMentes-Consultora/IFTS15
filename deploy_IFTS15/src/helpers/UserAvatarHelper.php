<?php
// helpers/UserAvatarHelper.php
// Devuelve la URL de la foto de perfil del usuario logueado, siempre actualizada desde la BD

use App\Model\User;

function getUserAvatarUrl($conn) {
    if (!$conn instanceof mysqli) return null;
    if (!isset($_SESSION['user_id'])) return null;
    $userId = $_SESSION['user_id'];
    $datos = User::obtenerUsuarioCompleto($conn, $userId);
    return $datos['foto_perfil_url'] ?? null;
}
