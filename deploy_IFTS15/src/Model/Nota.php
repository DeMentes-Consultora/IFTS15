<?php
namespace App\Model;
// src/Model/Nota.php
// Modelo para acceder a la tabla notas (solo lectura para el perfil de alumno)

class Nota {
    // Obtener todas las notas de un usuario (alumno)
    public static function obtenerPorUsuario($conn, $id_usuario) {
        $stmt = $conn->prepare("SELECT n.*, m.nombre_materia FROM notas n INNER JOIN materia m ON n.id_materia = m.id_materia WHERE n.id_usuario = ? AND n.habilitado = 1 AND n.cancelado = 0");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    // (Opcional) Obtener todas las notas de una materia
    public static function obtenerPorMateria($conn, $id_materia) {
        $stmt = $conn->prepare("SELECT n.*, u.email FROM notas n INNER JOIN usuario u ON n.id_usuario = u.id_usuario WHERE n.id_materia = ? AND n.habilitado = 1 AND n.cancelado = 0");
        $stmt->bind_param("i", $id_materia);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
