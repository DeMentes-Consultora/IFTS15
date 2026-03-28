<?php
namespace App\Controllers;

require_once __DIR__ . '/../Model/indexSql.php';

class EstadisticasController {
    // Devuelve la cantidad de alumnos (id_rol = 1)
    public static function getCantidadAlumnos($conn) {
        $result = $conn->query(SQL_CANTIDAD_ALUMNOS);
        if ($row = $result->fetch_assoc()) {
            return (int)$row['total'];
        }
        return 0;
    }

    // Devuelve la cantidad de profesores (id_rol = 2)
    public static function getCantidadProfesores($conn) {
        $result = $conn->query(SQL_CANTIDAD_PROFESORES);
        if ($row = $result->fetch_assoc()) {
            return (int)$row['total'];
        }
        return 0;
    }

    // Devuelve la cantidad de carreras
    public static function getCantidadCarreras($conn) {
        $result = $conn->query(SQL_CANTIDAD_CARRERAS);
        if ($row = $result->fetch_assoc()) {
            return (int)$row['total'];
        }
        return 0;
    }
}
