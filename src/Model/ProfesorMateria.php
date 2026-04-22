<?php

namespace App\Model;

class ProfesorMateria
{
    public static function asegurarTabla($conn)
    {
        $sql = "CREATE TABLE IF NOT EXISTS profesor_materia (
            id_profesor_materia INT(11) NOT NULL AUTO_INCREMENT,
            id_profesor INT(11) NOT NULL,
            id_materia INT(11) NOT NULL,
            habilitado INT(1) NOT NULL DEFAULT 1,
            cancelado INT(1) NOT NULL DEFAULT 0,
            idCreate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            idUpdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id_profesor_materia),
            UNIQUE KEY uq_profesor_materia (id_profesor, id_materia),
            KEY idx_pm_materia (id_materia),
            CONSTRAINT fk_pm_profesor FOREIGN KEY (id_profesor) REFERENCES usuario (id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_pm_materia FOREIGN KEY (id_materia) REFERENCES materia (id_materia) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";

        return $conn->query($sql);
    }

    public static function obtenerProfesores($conn)
    {
        $sql = "SELECT u.id_usuario, p.nombre, p.apellido, u.email, u.id_carrera
                FROM usuario u
                INNER JOIN persona p ON p.id_persona = u.id_persona
                WHERE u.id_rol = 2
                  AND u.habilitado = 1
                  AND u.cancelado = 0
                  AND p.habilitado = 1
                  AND p.cancelado = 0
                ORDER BY p.apellido ASC, p.nombre ASC";

        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function obtenerCarreras($conn)
    {
        $sql = "SELECT id_carrera, nombreCarrera AS carrera
                FROM carrera
                WHERE habilitado = 1 AND cancelado = 0
                ORDER BY nombreCarrera ASC";
        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function obtenerAsignacionesPorProfesor($conn, $idProfesor)
    {
        $sql = "SELECT id_materia
                FROM profesor_materia
                WHERE id_profesor = ?
                  AND habilitado = 1
                  AND cancelado = 0";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $idProfesor);
        $stmt->execute();
        $result = $stmt->get_result();

        $map = [];
        while ($row = $result->fetch_assoc()) {
            $map[(int)$row['id_materia']] = true;
        }

        return $map;
    }

    public static function obtenerTablaAsignacion($conn, $idProfesor, $idCarrera = 0, $idMateria = 0)
    {
        $sql = "SELECT
                    m.id_materia,
                    m.nombre_materia,
                    c.id_carrera,
                    c.nombreCarrera AS carrera,
                    CASE WHEN pm.id_profesor_materia IS NULL THEN 0 ELSE 1 END AS asignada
                FROM materia m
                LEFT JOIN carrera c ON c.id_carrera = m.id_carrera
                LEFT JOIN profesor_materia pm
                    ON pm.id_materia = m.id_materia
                    AND pm.id_profesor = ?
                    AND pm.habilitado = 1
                    AND pm.cancelado = 0
                WHERE m.habilitado = 1
                  AND m.cancelado = 0
                  AND (? = 0 OR c.id_carrera = ?)
                  AND (? = 0 OR m.id_materia = ?)
                ORDER BY c.nombreCarrera ASC, m.nombre_materia ASC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('iiiii', $idProfesor, $idCarrera, $idCarrera, $idMateria, $idMateria);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function toggleAsignacion($conn, $idProfesor, $idMateria, $asignar)
    {
        if ($asignar) {
            $sql = "INSERT INTO profesor_materia (id_profesor, id_materia)
                    VALUES (?, ?)
                    ON DUPLICATE KEY UPDATE
                        habilitado = 1,
                        cancelado = 0,
                        idUpdate = CURRENT_TIMESTAMP";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                return false;
            }
            $stmt->bind_param('ii', $idProfesor, $idMateria);
            return $stmt->execute();
        }

        $sql = "UPDATE profesor_materia
                SET habilitado = 0,
                    cancelado = 1,
                    idUpdate = CURRENT_TIMESTAMP
                WHERE id_profesor = ?
                  AND id_materia = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ii', $idProfesor, $idMateria);
        return $stmt->execute();
    }

    public static function esProfesorValido($conn, $idProfesor)
    {
        $sql = "SELECT id_usuario
                FROM usuario
                WHERE id_usuario = ?
                  AND id_rol = 2
                  AND habilitado = 1
                  AND cancelado = 0
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $idProfesor);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public static function esMateriaValida($conn, $idMateria)
    {
        $sql = "SELECT id_materia
                FROM materia
                WHERE id_materia = ?
                  AND habilitado = 1
                  AND cancelado = 0
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $idMateria);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }
}
