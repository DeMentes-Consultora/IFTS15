<?php
require_once __DIR__ . '/src/config.php';
use App\Model\PasswordReset;
use App\Model\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$token || !$password || !$confirm) {
        header('Location: resetear.php?token=' . urlencode($token) . '&error=campos_vacios');
        exit;
    }
    if ($password !== $confirm) {
        header('Location: resetear.php?token=' . urlencode($token) . '&error=confirmacion');
        exit;
    }
    $conn = getConnection();
    $reset = PasswordReset::obtenerPorToken($conn, $token);
    if (!$reset) {
        header('Location: resetear.php?token=' . urlencode($token) . '&error=token_invalido');
        exit;
    }
    $user = User::buscarPorId($conn, $reset['user_id']);
    if (!$user) {
        header('Location: resetear.php?token=' . urlencode($token) . '&error=usuario_no_encontrado');
        exit;
    }
    $user->setPassword($password);
    $user->actualizar($conn);
    PasswordReset::marcarComoUsado($conn, $reset['id']);
    header('Location: login.php?reset=ok');
    exit;
}
header('Location: login.php');
exit;
