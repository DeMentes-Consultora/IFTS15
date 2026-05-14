<?php
namespace App\Model;

use Exception;

class PasswordReset
{
    public static function crear($conn, $user_id, $token, $expires_at)
    {
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $token, $expires_at);
        return $stmt->execute();
    }

    public static function obtenerPorToken($conn, $token)
    {
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND used = 0 AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public static function marcarComoUsado($conn, $id)
    {
        $stmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function eliminarExpirados($conn)
    {
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE expires_at < NOW() OR used = 1");
        return $stmt->execute();
    }
}
