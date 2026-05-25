<?php

namespace App\Model;

use Exception;

class PostulacionBolsaTrabajo
{
    public static function obtenerPorOfertaYUsuario($conn, int $idOferta, int $idUsuario): ?array
    {
        $stmt = $conn->prepare(
            'SELECT id_postulacion_bolsa_trabajo, cancelado, cv_url, cv_public_id
             FROM postulacion_bolsa_trabajo
             WHERE id_bolsa_trabajo = ? AND id_usuario = ?
             LIMIT 1'
        );
        $stmt->bind_param('ii', $idOferta, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public static function obtenerActivasDeAlumno($conn, int $idUsuario): array
    {
        $stmt = $conn->prepare(
            "SELECT
                pbt.id_postulacion_bolsa_trabajo,
                pbt.id_bolsa_trabajo,
                pbt.cv_url,
                pbt.idCreate AS fecha_postulacion,
                bt.titulo_oferta,
                bt.texto_oferta,
                u.email,
                pe.nombre,
                pe.apellido,
                pe.telefono
             FROM postulacion_bolsa_trabajo pbt
             INNER JOIN bolsa_trabajo bt ON bt.id_bolsa_trabajo = pbt.id_bolsa_trabajo
             INNER JOIN usuario u ON u.id_usuario = bt.id_usuario
             INNER JOIN persona pe ON pe.id_persona = u.id_persona
             WHERE pbt.id_usuario = ?
               AND pbt.cancelado = 0
               AND bt.habilitado = 1
               AND bt.cancelado = 0
             ORDER BY pbt.idCreate DESC"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerMapaActivasPorUsuario($conn, int $idUsuario): array
    {
        $stmt = $conn->prepare(
            'SELECT id_postulacion_bolsa_trabajo, id_bolsa_trabajo, cv_url
             FROM postulacion_bolsa_trabajo
             WHERE id_usuario = ? AND cancelado = 0'
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $map = [];
        while ($row = $result->fetch_assoc()) {
            $map[(int)$row['id_bolsa_trabajo']] = [
                'id_postulacion_bolsa_trabajo' => (int)$row['id_postulacion_bolsa_trabajo'],
                'cv_url' => $row['cv_url'] ?? null,
            ];
        }

        return $map;
    }

    public static function crearPostulacion($conn, int $idOferta, int $idUsuario, ?string $cvUrl, ?string $cvPublicId): ?int
    {
        $stmt = $conn->prepare(
            'INSERT INTO postulacion_bolsa_trabajo (id_bolsa_trabajo, id_usuario, cv_url, cv_public_id, cancelado)
             VALUES (?, ?, ?, ?, 0)'
        );
        $stmt->bind_param('iiss', $idOferta, $idUsuario, $cvUrl, $cvPublicId);

        if (!$stmt->execute()) {
            return null;
        }

        return (int)$conn->insert_id;
    }

    public static function reactivarPostulacion($conn, int $idPostulacion, ?string $cvUrl, ?string $cvPublicId): bool
    {
        $stmt = $conn->prepare(
            'UPDATE postulacion_bolsa_trabajo
             SET cancelado = 0, cv_url = ?, cv_public_id = ?
             WHERE id_postulacion_bolsa_trabajo = ?'
        );
        $stmt->bind_param('ssi', $cvUrl, $cvPublicId, $idPostulacion);
        return $stmt->execute();
    }

    public static function cancelarDeAlumno($conn, int $idPostulacion, int $idUsuario): bool
    {
        $stmt = $conn->prepare(
            'UPDATE postulacion_bolsa_trabajo
             SET cancelado = 1
             WHERE id_postulacion_bolsa_trabajo = ? AND id_usuario = ? AND cancelado = 0'
        );
        $stmt->bind_param('ii', $idPostulacion, $idUsuario);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}