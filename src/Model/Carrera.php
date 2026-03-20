<?php
/**
 * Modelo: Carrera
 * 
 * Gestiona las operaciones CRUD de carreras en la base de datos.
 * Una carrera puede tener múltiples materias asociadas.
 * 
 * @package App\Model
 * @author IFTS15 Team
 */

namespace App\Model;

use mysqli;

class Carrera
{
    /**
     * Obtener todas las carreras
     * 
     * @param mysqli $conn Conexión a la base de datos
     * @param bool $soloHabilitadas Si true, solo devuelve carreras habilitadas
     * @return array Array de carreras ordenadas alfabéticamente
     */
    public static function obtenerTodas($conn, $soloHabilitadas = true)
    {
        $sql = "SELECT * FROM carrera";
        if ($soloHabilitadas) {
            $sql .= " WHERE habilitado = 1";
        }
        $sql .= " ORDER BY carrera ASC";
        
        $result = $conn->query($sql);
        $carreras = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $carreras[] = $row;
            }
        }
        return $carreras;
    }

    /**
     * Obtener carrera por ID
     * 
     * @param mysqli $conn Conexión a la base de datos
     * @param int $id ID de la carrera
     * @return array|null Datos de la carrera o null si no existe
     */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "SELECT * FROM carrera WHERE id_carrera = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Crear nueva carrera
     * 
     * @param mysqli $conn Conexión a la base de datos
     * @param string $nombre Nombre de la carrera
     * @return int|false ID de la carrera creada o false si falló
     */
    public static function crear($conn, $nombre)
    {
        $sql = "INSERT INTO carrera (carrera, habilitado, cancelado) VALUES (?, 1, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $nombre);
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        return false;
    }

    /**
     * Actualizar carrera
     */
    public static function actualizar($conn, $id, $nombre)
    {
        $sql = "UPDATE carrera SET carrera = ? WHERE id_carrera = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $nombre, $id);
        return $stmt->execute();
    }

    /**
     * Eliminar carrera (soft delete)
     */
    public static function eliminar($conn, $id)
    {
        // Primero liberar las materias asociadas (quitar id_carrera de las materias)
        $sql = "UPDATE materia SET id_carrera = NULL WHERE id_carrera = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        // Luego marcar como cancelada
        $sql = "UPDATE carrera SET habilitado = 0, cancelado = 1 WHERE id_carrera = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Asociar materia a carrera
     */
    public static function asociarMateria($conn, $idCarrera, $idMateria)
    {
        // Ahora permitimos que una materia esté en múltiples carreras
        // Solo verificamos que no esté duplicada en la MISMA carrera
        $sql = "SELECT id_carrera FROM materia WHERE id_materia = ? AND id_carrera = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $idMateria, $idCarrera);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'error' => 'La materia ya está asociada a esta carrera'];
        }
        
        // Actualizar id_carrera (ahora sí permitimos que haya otras con el mismo nombre en otras carreras)
        $sql = "UPDATE materia SET id_carrera = ? WHERE id_materia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $idCarrera, $idMateria);
        
        if ($stmt->execute()) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Error al asociar materia'];
    }

    /**
     * Desasociar materia de carrera
     */
    public static function desasociarMateria($conn, $idCarrera, $idMateria)
    {
        $sql = "UPDATE materia SET id_carrera = NULL WHERE id_materia = ? AND id_carrera = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $idMateria, $idCarrera);
        return $stmt->execute();
    }

    /**
     * Obtener materias asociadas a una carrera
     */
    public static function obtenerMaterias($conn, $idCarrera)
    {
        $sql = "SELECT * FROM materia 
                WHERE id_carrera = ? AND habilitado = 1 
                ORDER BY nombre_materia ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $idCarrera);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }
        return $materias;
    }
}
