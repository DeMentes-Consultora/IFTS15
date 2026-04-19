<?php
// src/services/PerfilService.php
// Servicio para centralizar la obtención de todos los datos del perfil de usuario

namespace App\Services;

use App\Model\User;
use App\Model\Nota;
use App\Model\Person;
use App\Model\Carrera;
use App\Model\Materia;

class PerfilService {
    public static function obtenerDatosPerfil($conn, $id_usuario) {
        // Obtener datos de usuario (incluye id_persona, id_carrera, etc.)
        $usuario = User::obtenerUsuarioCompleto($conn, $id_usuario);
        if (!$usuario) return null;

        // Obtener datos personales
        $persona = Person::buscarPorId($conn, $usuario['id_persona']);
        // Obtener carrera
        $carrera = null;
        if (!empty($usuario['id_carrera'])) {
            $carrera = Carrera::obtenerPorId($conn, $usuario['id_carrera']);
        }
        // Obtener comisión, año, etc. si es necesario (puedes agregar más lógica aquí)

        // Obtener notas y materias
        $notas = Nota::obtenerPorUsuario($conn, $id_usuario);

        // Obtener materias asociadas a la carrera del usuario
        $materiasCarrera = [];
        if (!empty($usuario['id_carrera'])) {
            $materiasCarrera = Materia::obtenerPorCarrera($conn, (int)$usuario['id_carrera']);
        }

        // Indexar la última nota por materia para mostrar estado académico en el perfil
        $notasPorMateria = [];
        foreach ($notas as $nota) {
            $idMateria = (int)($nota['id_materia'] ?? 0);
            if ($idMateria <= 0) {
                continue;
            }

            $fechaActual = $nota['idCreate'] ?? null;
            $fechaGuardada = $notasPorMateria[$idMateria]['idCreate'] ?? null;

            if (!isset($notasPorMateria[$idMateria]) || ($fechaActual && (!$fechaGuardada || $fechaActual > $fechaGuardada))) {
                $notasPorMateria[$idMateria] = $nota;
            }
        }

        $materiasPerfil = [];
        foreach ($materiasCarrera as $materia) {
            $idMateria = (int)($materia['id_materia'] ?? 0);
            $notaMateria = $notasPorMateria[$idMateria] ?? null;

            $materiasPerfil[] = [
                'id_materia' => $idMateria,
                'nombre_materia' => $materia['nombre_materia'] ?? '',
                'nota' => $notaMateria['nota'] ?? null,
                'fecha_nota' => $notaMateria['fecha'] ?? ($notaMateria['idCreate'] ?? null)
            ];
        }

        return [
            'usuario' => $usuario,
            'persona' => $persona,
            'carrera' => $carrera,
            'notas' => $notas,
            'materias_perfil' => $materiasPerfil
        ];
    }
}
