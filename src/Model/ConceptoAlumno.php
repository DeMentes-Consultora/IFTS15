<?php
namespace App\Model;

class ConceptoAlumno {
    public static function obtenerPorAlumnoMateria($conn, $idUsuario, $idMateria) {
        $sql = "SELECT * FROM conceptos_alumno WHERE id_usuario = ? AND id_materia = ? AND habilitado = 1 AND cancelado = 0 ORDER BY id_concepto ASC LIMIT 5";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $idUsuario, $idMateria);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function guardarConceptos($conn, $idUsuario, $idMateria, $conceptos) {
        // $conceptos: array de ['concepto' => string, 'nota' => float]
        $conn->begin_transaction();
        try {
            // Eliminar conceptos previos
            $del = $conn->prepare("DELETE FROM conceptos_alumno WHERE id_usuario = ? AND id_materia = ?");
            $del->bind_param('ii', $idUsuario, $idMateria);
            $del->execute();
            // Insertar nuevos conceptos
            $ins = $conn->prepare("INSERT INTO conceptos_alumno (id_usuario, id_materia, concepto, nota) VALUES (?, ?, ?, ?)");
            foreach ($conceptos as $c) {
                $ins->bind_param('iisd', $idUsuario, $idMateria, $c['concepto'], $c['nota']);
                $ins->execute();
            }
            $conn->commit();
            return ['success' => true, 'message' => 'Conceptos guardados'];
        } catch (\Exception $e) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Error al guardar conceptos: ' . $e->getMessage()];
        }
    }
}
