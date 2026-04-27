<?php
namespace App\Model;
// src/Model/Nota.php
// Modelo para acceder a la tabla notas (solo lectura para el perfil de alumno)

class Nota {
    private static function obtenerColumnaPrimerParcial($conn)
    {
        $resNueva = $conn->query("SHOW COLUMNS FROM notas LIKE '1er_parcial'");
        if ($resNueva && $resNueva->num_rows > 0) {
            return '1er_parcial';
        }

        // Compatibilidad con esquema viejo que tenia un typo.
        $resVieja = $conn->query("SHOW COLUMNS FROM notas LIKE '1er_piarcial'");
        if ($resVieja && $resVieja->num_rows > 0) {
            return '1er_piarcial';
        }

        return null;
    }

    public static function obtenerColumnasNotas($conn)
    {
        return [
            'p1' => self::obtenerColumnaPrimerParcial($conn),
            'p2' => '2do_parcial',
            'final' => 'final'
        ];
    }

    // Obtener todas las notas de un usuario (alumno)
    public static function obtenerPorUsuario($conn, $id_usuario) {
        $columnas = self::obtenerColumnasNotas($conn);
        if (empty($columnas['p1'])) {
            return [];
        }

        $sql = "SELECT n.id_nota,
                       n.id_usuario,
                       n.id_materia,
                       n.`{$columnas['p1']}` AS nota_p1,
                       n.`{$columnas['p2']}` AS nota_p2,
                       n.`{$columnas['final']}` AS nota_final,
                       n.habilitado,
                       n.cancelado,
                       n.idCreate,
                       n.idUpdate,
                       m.nombre_materia
                FROM notas n
                INNER JOIN materia m ON n.id_materia = m.id_materia
                WHERE n.id_usuario = ?
                  AND n.habilitado = 1
                  AND n.cancelado = 0";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // (Opcional) Obtener todas las notas de una materia
    public static function obtenerPorMateria($conn, $id_materia) {
        $columnas = self::obtenerColumnasNotas($conn);
        if (empty($columnas['p1'])) {
            return [];
        }

        $sql = "SELECT n.id_nota,
                       n.id_usuario,
                       n.id_materia,
                       n.`{$columnas['p1']}` AS nota_p1,
                       n.`{$columnas['p2']}` AS nota_p2,
                       n.`{$columnas['final']}` AS nota_final,
                       n.habilitado,
                       n.cancelado,
                       n.idCreate,
                       n.idUpdate,
                       u.email
                FROM notas n
                INNER JOIN usuario u ON n.id_usuario = u.id_usuario
                WHERE n.id_materia = ?
                  AND n.habilitado = 1
                  AND n.cancelado = 0";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_materia);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function guardarNotas($conn, $idUsuario, $idMateria, $notaP1, $notaP2, $notaFinal)
    {
        $columnas = self::obtenerColumnasNotas($conn);
        if (empty($columnas['p1'])) {
            return ['success' => false, 'message' => 'No se encontró la columna de primer parcial en la tabla notas.'];
        }

        $sqlExistente = "SELECT id_nota,
                                `{$columnas['p1']}` AS nota_p1,
                                `{$columnas['p2']}` AS nota_p2,
                                `{$columnas['final']}` AS nota_final
                         FROM notas
                         WHERE id_usuario = ?
                           AND id_materia = ?
                         ORDER BY id_nota DESC
                         LIMIT 1";

        $stmtExistente = $conn->prepare($sqlExistente);
        if (!$stmtExistente) {
            return ['success' => false, 'message' => 'No se pudo preparar la consulta de notas existentes.'];
        }

        $stmtExistente->bind_param('ii', $idUsuario, $idMateria);
        $stmtExistente->execute();
        $existente = $stmtExistente->get_result()->fetch_assoc();

        $valorP1 = $notaP1;
        $valorP2 = $notaP2;
        $valorFinal = $notaFinal;

        if ($existente) {
            if ($valorP1 === null) {
                $valorP1 = isset($existente['nota_p1']) ? (int)$existente['nota_p1'] : 0;
            }
            if ($valorP2 === null) {
                $valorP2 = isset($existente['nota_p2']) ? (int)$existente['nota_p2'] : 0;
            }
            if ($valorFinal === null) {
                $valorFinal = isset($existente['nota_final']) ? (int)$existente['nota_final'] : 0;
            }

            $sqlUpdate = "UPDATE notas
                          SET `{$columnas['p1']}` = ?,
                              `{$columnas['p2']}` = ?,
                              `{$columnas['final']}` = ?,
                              habilitado = 1,
                              cancelado = 0
                          WHERE id_nota = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            if (!$stmtUpdate) {
                return ['success' => false, 'message' => 'No se pudo preparar la actualización de notas.'];
            }

            $idNota = (int)$existente['id_nota'];
            $stmtUpdate->bind_param('iiii', $valorP1, $valorP2, $valorFinal, $idNota);
            if (!$stmtUpdate->execute()) {
                return ['success' => false, 'message' => 'Error al actualizar las notas.'];
            }

            return ['success' => true, 'message' => 'Notas actualizadas correctamente.'];
        }

        if ($valorP1 === null) {
            $valorP1 = 0;
        }
        if ($valorP2 === null) {
            $valorP2 = 0;
        }
        if ($valorFinal === null) {
            $valorFinal = 0;
        }

        $sqlInsert = "INSERT INTO notas (id_usuario, id_materia, `{$columnas['p1']}`, `{$columnas['p2']}`, `{$columnas['final']}`, habilitado, cancelado)
                      VALUES (?, ?, ?, ?, ?, 1, 0)";
        $stmtInsert = $conn->prepare($sqlInsert);
        if (!$stmtInsert) {
            return ['success' => false, 'message' => 'No se pudo preparar el alta de notas.'];
        }
        $stmtInsert->bind_param('iiiii', $idUsuario, $idMateria, $valorP1, $valorP2, $valorFinal);
        if (!$stmtInsert->execute()) {
            return ['success' => false, 'message' => 'Error al registrar las notas.'];
        }

        return ['success' => true, 'message' => 'Notas guardadas correctamente.'];
    }
}
