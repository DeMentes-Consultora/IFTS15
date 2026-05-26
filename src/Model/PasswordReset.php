<?php
namespace App\Model;

use Exception;

class PasswordReset
{
    private static function hasColumn($conn, $column)
    {
        $column = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $column);
        $result = $conn->query("SHOW COLUMNS FROM password_resets LIKE '" . $conn->real_escape_string($column) . "'");
        return $result !== false && $result->num_rows > 0;
    }

    public static function crear($conn, $user_id, $token, $expires_at, $email = null)
    {
        if (self::hasColumn($conn, 'user_id')) {
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('No se pudo preparar el alta de password_resets: ' . $conn->error);
            }
            $stmt->bind_param("iss", $user_id, $token, $expires_at);
            if (!$stmt->execute()) {
                throw new Exception('No se pudo guardar el token de recupero: ' . $stmt->error);
            }

            return true;
        }

        if (!self::hasColumn($conn, 'email')) {
            throw new Exception('La tabla password_resets no tiene ni user_id ni email. Falta aplicar la migracion.');
        }

        $email = trim((string) $email);
        if ($email === '') {
            throw new Exception('No se pudo guardar el token de recupero porque falta el email del usuario.');
        }

        $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        if ($deleteStmt === false) {
            throw new Exception('No se pudo preparar la limpieza previa de password_resets: ' . $conn->error);
        }
        $deleteStmt->bind_param("s", $email);
        if (!$deleteStmt->execute()) {
            throw new Exception('No se pudo limpiar el token previo de recupero: ' . $deleteStmt->error);
        }

        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        if ($stmt === false) {
            throw new Exception('No se pudo preparar el alta legacy de password_resets: ' . $conn->error);
        }
        $stmt->bind_param("sss", $email, $token, $expires_at);
        if (!$stmt->execute()) {
            throw new Exception('No se pudo guardar el token legacy de recupero: ' . $stmt->error);
        }

        return true;
    }

    public static function obtenerPorToken($conn, $token)
    {
        if (self::hasColumn($conn, 'user_id')) {
            $sql = "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()";
            if (self::hasColumn($conn, 'used')) {
                $sql .= " AND used = 0";
            }
        } elseif (self::hasColumn($conn, 'email')) {
            $sql = "SELECT pr.*, u.id_usuario AS user_id FROM password_resets pr LEFT JOIN usuario u ON u.email = pr.email WHERE pr.token = ? AND pr.expires_at > NOW()";
            if (self::hasColumn($conn, 'used')) {
                $sql .= " AND pr.used = 0";
            }
        } else {
            throw new Exception('La tabla password_resets no tiene estructura compatible para consultar tokens.');
        }

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('No se pudo preparar la consulta de password_resets: ' . $conn->error);
        }
        $stmt->bind_param("s", $token);
        if (!$stmt->execute()) {
            throw new Exception('No se pudo consultar el token de recupero: ' . $stmt->error);
        }
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public static function marcarComoUsado($conn, $id)
    {
        if (!self::hasColumn($conn, 'used')) {
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE id = ?");
            if ($stmt === false) {
                throw new Exception('No se pudo preparar la eliminación del token legacy: ' . $conn->error);
            }
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception('No se pudo eliminar el token legacy: ' . $stmt->error);
            }

            return true;
        }

        $stmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
        if ($stmt === false) {
            throw new Exception('No se pudo preparar la actualización de password_resets: ' . $conn->error);
        }
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception('No se pudo marcar el token como usado: ' . $stmt->error);
        }

        return true;
    }

    public static function eliminarExpirados($conn)
    {
        $sql = "DELETE FROM password_resets WHERE expires_at < NOW()";
        if (self::hasColumn($conn, 'used')) {
            $sql .= " OR used = 1";
        }

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('No se pudo preparar la limpieza de password_resets: ' . $conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception('No se pudo limpiar tokens expirados: ' . $stmt->error);
        }

        return true;
    }
}
