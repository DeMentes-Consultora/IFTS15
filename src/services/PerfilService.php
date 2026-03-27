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

        return [
            'usuario' => $usuario,
            'persona' => $persona,
            'carrera' => $carrera,
            'notas' => $notas
        ];
    }
}
