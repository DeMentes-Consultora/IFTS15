<?php

namespace App\Model;

use Exception;

class BolsaTrabajo
{
    public static function obtenerPendientes($conn): array
    {
        $sql = "SELECT
                    b.id_bolsa_trabajo,
                    b.titulo_oferta,
                    b.texto_oferta,
                    b.habilitado,
                    b.cancelado,
                    b.idCreate AS fecha_creacion,
                    u.id_usuario,
                    u.email,
                    p.nombre,
                    p.apellido,
                    p.telefono
                FROM bolsa_trabajo b
                INNER JOIN usuario u ON u.id_usuario = b.id_usuario
                INNER JOIN persona p ON p.id_persona = u.id_persona
                WHERE b.habilitado = 0
                  AND b.cancelado = 0
                ORDER BY b.idCreate DESC";

        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception('No se pudieron obtener las ofertas pendientes');
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPublicadas($conn): array
    {
        $sql = "SELECT
                    b.id_bolsa_trabajo,
                    b.titulo_oferta,
                    b.texto_oferta,
                    b.habilitado,
                    b.cancelado,
                    b.idCreate AS fecha_creacion,
                    u.id_usuario,
                    u.email,
                    p.nombre,
                    p.apellido,
                    p.telefono,
                    c.nombreCarrera AS carrera
                FROM bolsa_trabajo b
                INNER JOIN usuario u ON u.id_usuario = b.id_usuario
                INNER JOIN persona p ON p.id_persona = u.id_persona
                LEFT JOIN carrera c ON c.id_carrera = u.id_carrera
                WHERE b.habilitado = 1
                  AND b.cancelado = 0
                ORDER BY b.idCreate DESC";

        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception('No se pudieron obtener las ofertas publicadas');
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($conn, int $id): ?array
    {
        $stmt = $conn->prepare(
            "SELECT
                b.id_bolsa_trabajo,
                b.id_usuario,
                b.titulo_oferta,
                b.texto_oferta,
                b.habilitado,
                b.cancelado,
                b.idCreate AS fecha_creacion,
                u.email,
                p.nombre,
                p.apellido,
                p.telefono
             FROM bolsa_trabajo b
             INNER JOIN usuario u ON u.id_usuario = b.id_usuario
             INNER JOIN persona p ON p.id_persona = u.id_persona
             WHERE b.id_bolsa_trabajo = ?
             LIMIT 1"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public static function crearOferta($conn, int $idUsuario, string $titulo, string $texto): ?int
    {
        $stmt = $conn->prepare(
            'INSERT INTO bolsa_trabajo (id_usuario, titulo_oferta, texto_oferta, habilitado, cancelado) VALUES (?, ?, ?, 0, 0)'
        );
        $stmt->bind_param('iss', $idUsuario, $titulo, $texto);

        if (!$stmt->execute()) {
            return null;
        }

        return (int)$conn->insert_id;
    }

    public static function publicarOferta($conn, int $id): bool
    {
        $stmt = $conn->prepare(
            'UPDATE bolsa_trabajo SET habilitado = 1, cancelado = 0 WHERE id_bolsa_trabajo = ?'
        );
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function rechazarOferta($conn, int $id): bool
    {
        $stmt = $conn->prepare(
            'UPDATE bolsa_trabajo SET habilitado = 0, cancelado = 1 WHERE id_bolsa_trabajo = ?'
        );
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function deshabilitarOferta($conn, int $id): bool
    {
        $stmt = $conn->prepare(
            'UPDATE bolsa_trabajo SET habilitado = 0, cancelado = 0 WHERE id_bolsa_trabajo = ?'
        );
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function obtenerResumen($conn): array
    {
        $sql = "SELECT
                    SUM(CASE WHEN habilitado = 0 AND cancelado = 0 THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN habilitado = 1 AND cancelado = 0 THEN 1 ELSE 0 END) AS publicadas,
                    SUM(CASE WHEN cancelado = 1 THEN 1 ELSE 0 END) AS rechazadas
                FROM bolsa_trabajo";

        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception('No se pudo obtener el resumen de ofertas');
        }

        $row = $result->fetch_assoc() ?: [];
        return [
            'pendientes' => (int)($row['pendientes'] ?? 0),
            'publicadas' => (int)($row['publicadas'] ?? 0),
            'rechazadas' => (int)($row['rechazadas'] ?? 0),
        ];
    }
}