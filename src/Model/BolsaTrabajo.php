<?php

namespace App\Model;

use Exception;

class BolsaTrabajo
{
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

    public static function obtenerGestionConPostulaciones($conn): array
    {
        $sql = "SELECT
                    b.id_bolsa_trabajo,
                    b.titulo_oferta,
                    b.texto_oferta,
                    b.habilitado,
                    b.cancelado,
                    b.idCreate AS fecha_creacion,
                    pbt.id_postulacion_bolsa_trabajo,
                    pbt.cv_url,
                    pbt.idCreate AS fecha_postulacion,
                    up.email AS email_postulante,
                    pp.apellido AS apellido_postulante,
                    pp.nombre AS nombre_postulante,
                    pp.foto_perfil_url
                FROM bolsa_trabajo b
                LEFT JOIN postulacion_bolsa_trabajo pbt
                       ON pbt.id_bolsa_trabajo = b.id_bolsa_trabajo
                      AND pbt.cancelado = 0
                LEFT JOIN usuario up ON up.id_usuario = pbt.id_usuario
                LEFT JOIN persona pp ON pp.id_persona = up.id_persona
                WHERE b.cancelado = 0
                ORDER BY b.idCreate DESC, pbt.idCreate DESC";

        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception('No se pudieron obtener las ofertas gestionadas');
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerOfertasGestion($conn): array
    {
        $sql = "SELECT
                    b.id_bolsa_trabajo,
                    b.titulo_oferta,
                    b.texto_oferta,
                    b.habilitado,
                    b.cancelado,
                    b.idCreate AS fecha_creacion,
                    COUNT(CASE WHEN pbt.cancelado = 0 THEN 1 END) AS postulaciones_totales
                FROM bolsa_trabajo b
                LEFT JOIN postulacion_bolsa_trabajo pbt
                       ON pbt.id_bolsa_trabajo = b.id_bolsa_trabajo
                WHERE b.cancelado = 0
                GROUP BY b.id_bolsa_trabajo, b.titulo_oferta, b.texto_oferta, b.habilitado, b.cancelado, b.idCreate
                ORDER BY b.idCreate DESC";

        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception('No se pudieron obtener las ofertas para gestion');
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
            'INSERT INTO bolsa_trabajo (id_usuario, titulo_oferta, texto_oferta, habilitado, cancelado) VALUES (?, ?, ?, 1, 0)'
        );
        $stmt->bind_param('iss', $idUsuario, $titulo, $texto);

        if (!$stmt->execute()) {
            return null;
        }

        return (int)$conn->insert_id;
    }

    public static function activarOferta($conn, int $id): bool
    {
        $stmt = $conn->prepare(
            'UPDATE bolsa_trabajo SET habilitado = 1, cancelado = 0 WHERE id_bolsa_trabajo = ?'
        );
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function deshabilitarOferta($conn, int $id): bool
    {
        $stmt = $conn->prepare(
            'UPDATE bolsa_trabajo SET habilitado = 0 WHERE id_bolsa_trabajo = ? AND cancelado = 0'
        );
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function eliminarOferta($conn, int $id): bool
    {
        $stmt = $conn->prepare(
            'UPDATE bolsa_trabajo SET habilitado = 0, cancelado = 1 WHERE id_bolsa_trabajo = ?'
        );
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function obtenerResumen($conn): array
    {
        $sql = "SELECT
                    SUM(CASE WHEN habilitado = 1 AND cancelado = 0 THEN 1 ELSE 0 END) AS publicadas,
                    SUM(CASE WHEN habilitado = 0 AND cancelado = 0 THEN 1 ELSE 0 END) AS inactivas,
                    SUM(CASE WHEN cancelado = 1 THEN 1 ELSE 0 END) AS ocultas,
                    (SELECT COUNT(*) FROM postulacion_bolsa_trabajo WHERE cancelado = 0) AS postulaciones
                FROM bolsa_trabajo";

        $result = $conn->query($sql);
        if (!$result) {
            throw new Exception('No se pudo obtener el resumen de ofertas');
        }

        $row = $result->fetch_assoc() ?: [];
        return [
            'publicadas' => (int)($row['publicadas'] ?? 0),
            'inactivas' => (int)($row['inactivas'] ?? 0),
            'ocultas' => (int)($row['ocultas'] ?? 0),
            'postulaciones' => (int)($row['postulaciones'] ?? 0),
        ];
    }
}