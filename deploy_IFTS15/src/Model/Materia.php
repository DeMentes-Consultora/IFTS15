<?php
/**
 * Modelo: Materia
 * 
 * Gestiona las operaciones CRUD de materias en la base de datos.
 * Las materias pueden estar asociadas a una carrera o estar libres.
 * 
 * @package App\Model
 * @author IFTS15 Team
 */

namespace App\Model;

use mysqli;

class Materia
{
    /**
     * Obtener todas las materias con filtros opcionales
     * 
     * @param mysqli $conn Conexión a la base de datos
     * @param bool $soloHabilitadas Si true, solo devuelve materias habilitadas
     * @param bool $soloLibres Si true, solo devuelve materias no asociadas a ninguna carrera
     * @return array Array de materias ordenadas alfabéticamente
     */
    public static function obtenerTodas($conn, $soloHabilitadas = true, $soloLibres = false)
    {
        $sql = "SELECT * FROM materia";
        $conditions = [];
        
        if ($soloHabilitadas) {
            $conditions[] = "habilitado = 1";
        }
        
        // Si solo queremos materias libres (no asociadas a ninguna carrera)
        if ($soloLibres) {
            $conditions[] = "id_carrera IS NULL";
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY nombre_materia ASC";
        
        $result = $conn->query($sql);
        $materias = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $materias[] = $row;
            }
        }
        return $materias;
    }

    /**
     * Obtener materia por ID
     * 
     * @param mysqli $conn Conexión a la base de datos
     * @param int $id ID de la materia
     * @return array|null Datos de la materia o null si no existe
     */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "SELECT * FROM materia WHERE id_materia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Crear nueva materia
     */
    public static function crear($conn, $nombre)
    {
        $sql = "INSERT INTO materia (nombre_materia, habilitado, cancelado) VALUES (?, 1, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $nombre);
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        return false;
    }

    /**
     * Actualizar materia
     */
    public static function actualizar($conn, $id, $nombre)
    {
        $sql = "UPDATE materia SET nombre_materia = ? WHERE id_materia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $nombre, $id);
        return $stmt->execute();
    }

    /**
     * Eliminar materia (soft delete)
     */
    public static function eliminar($conn, $id)
    {
        // Solo marcar como cancelada (la FK se encarga de la referencia)
        $sql = "UPDATE materia SET habilitado = 0, cancelado = 1 WHERE id_materia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Verificar si materia está asociada a alguna carrera
     */
    public static function estaAsociada($conn, $id)
    {
        $sql = "SELECT id_carrera FROM materia WHERE id_materia = ? AND id_carrera IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    /**
     * Obtener la carrera asociada a una materia
     */
    public static function obtenerCarreraAsociada($conn, $id)
    {
        $sql = "SELECT c.* FROM carrera c 
                INNER JOIN materia m ON m.id_carrera = c.id_carrera 
                WHERE m.id_materia = ? AND c.habilitado = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
