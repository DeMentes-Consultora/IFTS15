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
    private static function tablaExiste($conn, $tabla)
    {
        $tablaEscapada = $conn->real_escape_string($tabla);
        $res = $conn->query("SHOW TABLES LIKE '{$tablaEscapada}'");
        return $res && $res->num_rows > 0;
    }

    private static function asegurarTablasPerfilProfesor($conn)
    {
        $sqlProfesorMateria = "CREATE TABLE IF NOT EXISTS profesor_materia (
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

        $sqlMatricula = "CREATE TABLE IF NOT EXISTS matricula_materia (
            id_matricula_materia INT(11) NOT NULL AUTO_INCREMENT,
            id_usuario_alumno INT(11) NOT NULL,
            id_materia INT(11) NOT NULL,
            id_profesor INT(11) DEFAULT NULL,
            estado ENUM('espera','regular') NOT NULL DEFAULT 'espera',
            fecha_matriculacion TIMESTAMP NULL DEFAULT NULL,
            habilitado INT(1) NOT NULL DEFAULT 1,
            cancelado INT(1) NOT NULL DEFAULT 0,
            idCreate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            idUpdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id_matricula_materia),
            UNIQUE KEY uq_alumno_materia (id_usuario_alumno, id_materia),
            KEY idx_mm_materia (id_materia),
            KEY idx_mm_profesor (id_profesor),
            CONSTRAINT fk_mm_alumno FOREIGN KEY (id_usuario_alumno) REFERENCES usuario (id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_mm_materia FOREIGN KEY (id_materia) REFERENCES materia (id_materia) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_mm_profesor FOREIGN KEY (id_profesor) REFERENCES usuario (id_usuario) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";

        try {
            $conn->query($sqlProfesorMateria);
            $conn->query($sqlMatricula);

            // Backfill idempotente: asignar materias por carrera a profesores habilitados.
            $sqlBackfill = "INSERT INTO profesor_materia (id_profesor, id_materia)
                            SELECT u.id_usuario, m.id_materia
                            FROM usuario u
                            INNER JOIN materia m ON m.id_carrera = u.id_carrera
                            WHERE u.id_rol = 2
                              AND u.habilitado = 1
                              AND u.cancelado = 0
                              AND m.habilitado = 1
                              AND m.cancelado = 0
                            ON DUPLICATE KEY UPDATE idUpdate = CURRENT_TIMESTAMP";
            $conn->query($sqlBackfill);
        } catch (\Throwable $e) {
            error_log('[PerfilService::asegurarTablasPerfilProfesor] ' . $e->getMessage());
        }
    }

    public static function obtenerDatosPerfil($conn, $id_usuario, $filtros = []) {
        $usuario = User::obtenerUsuarioCompleto($conn, $id_usuario);
        if (!$usuario) {
            return null;
        }

        $persona = Person::buscarPorId($conn, $usuario['id_persona']);
        $carrera = null;
        if (!empty($usuario['id_carrera'])) {
            $carrera = Carrera::obtenerPorId($conn, $usuario['id_carrera']);
        }

        $idRol = (int)($usuario['id_rol'] ?? 0);
        if ($idRol === 2) {
            return self::obtenerDatosPerfilProfesor($conn, (int)$id_usuario, $usuario, $persona, $carrera, $filtros);
        }

        if ($idRol === 3) {
            return self::obtenerDatosPerfilAdministrativo($conn, (int)$id_usuario, $usuario, $persona, $carrera);
        }

        return self::obtenerDatosPerfilAlumno($conn, (int)$id_usuario, $usuario, $persona, $carrera);
    }

    private static function obtenerDatosPerfilAlumno($conn, $idUsuario, $usuario, $persona, $carrera)
    {
        $notas = Nota::obtenerPorUsuario($conn, $idUsuario);

        $materiasCarrera = [];
        if (!empty($usuario['id_carrera'])) {
            $materiasCarrera = Materia::obtenerPorCarrera($conn, (int)$usuario['id_carrera']);
        }

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
            'tipo_perfil' => 'alumno',
            'usuario' => $usuario,
            'persona' => $persona,
            'carrera' => $carrera,
            'notas' => $notas,
            'materias_perfil' => $materiasPerfil
        ];
    }

    private static function obtenerDatosPerfilAdministrativo($conn, $idUsuario, $usuario, $persona, $carrera)
    {
        $resumen = [
            'usuarios_pendientes' => 0,
            'usuarios_habilitados' => 0,
            'alumnos_habilitados' => 0,
            'profesores_habilitados' => 0,
        ];

        $qPendientes = $conn->query("SELECT COUNT(*) AS total FROM usuario WHERE habilitado = 0 AND cancelado = 1");
        if ($qPendientes) {
            $row = $qPendientes->fetch_assoc();
            $resumen['usuarios_pendientes'] = (int)($row['total'] ?? 0);
        }

        $qHabilitados = $conn->query("SELECT COUNT(*) AS total FROM usuario WHERE habilitado = 1 AND cancelado = 0");
        if ($qHabilitados) {
            $row = $qHabilitados->fetch_assoc();
            $resumen['usuarios_habilitados'] = (int)($row['total'] ?? 0);
        }

        $qAlumnos = $conn->query("SELECT COUNT(*) AS total FROM usuario WHERE id_rol = 1 AND habilitado = 1 AND cancelado = 0");
        if ($qAlumnos) {
            $row = $qAlumnos->fetch_assoc();
            $resumen['alumnos_habilitados'] = (int)($row['total'] ?? 0);
        }

        $qProfes = $conn->query("SELECT COUNT(*) AS total FROM usuario WHERE id_rol = 2 AND habilitado = 1 AND cancelado = 0");
        if ($qProfes) {
            $row = $qProfes->fetch_assoc();
            $resumen['profesores_habilitados'] = (int)($row['total'] ?? 0);
        }

        $usuariosPendientes = [];
        $sqlPendientes = "SELECT
                            u.id_usuario,
                            p.nombre,
                            p.apellido,
                            u.email,
                            r.rol AS nombre_rol,
                            u.idCreate
                          FROM usuario u
                          LEFT JOIN persona p ON p.id_persona = u.id_persona
                          LEFT JOIN roles r ON r.id_rol = u.id_rol
                          WHERE u.habilitado = 0
                            AND u.cancelado = 1
                          ORDER BY u.idCreate DESC
                          LIMIT 20";
        $resPend = $conn->query($sqlPendientes);
        if ($resPend) {
            $usuariosPendientes = $resPend->fetch_all(MYSQLI_ASSOC);
        }

        return [
            'tipo_perfil' => 'administrativo',
            'usuario' => $usuario,
            'persona' => $persona,
            'carrera' => $carrera,
            'resumen_admin' => $resumen,
            'usuarios_pendientes' => $usuariosPendientes,
        ];
    }

    private static function obtenerDatosPerfilProfesor($conn, $idProfesor, $usuario, $persona, $carrera, $filtros)
    {
        self::asegurarTablasPerfilProfesor($conn);

        $filtroCarrera = isset($filtros['id_carrera']) ? (int)$filtros['id_carrera'] : 0;
        $filtroMateria = isset($filtros['id_materia']) ? (int)$filtros['id_materia'] : 0;
        $filtroAnio = isset($filtros['id_anio_cursada']) ? (int)$filtros['id_anio_cursada'] : 0;

        $tieneRelacionProfesorMateria = self::tablaExiste($conn, 'profesor_materia');
        $tieneTablaMatricula = self::tablaExiste($conn, 'matricula_materia');

        if ($tieneRelacionProfesorMateria) {
            $sql = "SELECT
                    u.id_usuario AS id_alumno,
                    c.id_carrera,
                    c.nombreCarrera AS carrera,
                    m.id_materia,
                    m.nombre_materia AS materia,
                    co.comision,
                    ac.id_añoCursada AS id_anio_cursada,
                    ac.año AS anio,
                    p.apellido,
                    u.email,
                    " . ($tieneTablaMatricula ? "COALESCE(mm.estado, 'espera')" : "'espera'") . " AS estado_matricula
                FROM usuario u
                INNER JOIN persona p
                    ON p.id_persona = u.id_persona
                    AND p.habilitado = 1
                    AND p.cancelado = 0
                LEFT JOIN carrera c
                    ON c.id_carrera = u.id_carrera
                LEFT JOIN comision co
                    ON co.id_comision = u.id_comision
                LEFT JOIN añocursada ac
                    ON ac.id_añoCursada = u.id_añoCursada
                INNER JOIN materia m
                    ON m.id_carrera = u.id_carrera
                    AND m.habilitado = 1
                    AND m.cancelado = 0
                INNER JOIN profesor_materia pm
                    ON pm.id_materia = m.id_materia
                    AND pm.id_profesor = ?
                    AND pm.habilitado = 1
                    AND pm.cancelado = 0
                " . ($tieneTablaMatricula ? "LEFT JOIN matricula_materia mm
                    ON mm.id_usuario_alumno = u.id_usuario
                    AND mm.id_materia = m.id_materia
                    AND mm.habilitado = 1
                    AND mm.cancelado = 0" : "") . "
                WHERE u.id_rol = 1
                  AND u.habilitado = 1
                  AND u.cancelado = 0
                  AND (? = 0 OR u.id_carrera = ?)
                  AND (? = 0 OR ac.id_añoCursada = ?)
                  AND (? = 0 OR m.id_materia = ?)
                ORDER BY c.nombreCarrera ASC, m.nombre_materia ASC, ac.año ASC, p.apellido ASC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new \RuntimeException('No se pudo preparar la consulta de perfil profesor: ' . $conn->error);
            }

            $stmt->bind_param(
                'iiiiiii',
                $idProfesor,
                $filtroCarrera,
                $filtroCarrera,
                $filtroAnio,
                $filtroAnio,
                $filtroMateria,
                $filtroMateria
            );
            $stmt->execute();
            $result = $stmt->get_result();
            $filas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

            $materias = [];
            $sqlMaterias = "SELECT m.id_materia, m.nombre_materia, m.id_carrera
                            FROM profesor_materia pm
                            INNER JOIN materia m ON m.id_materia = pm.id_materia
                            WHERE pm.id_profesor = ?
                              AND pm.habilitado = 1
                              AND pm.cancelado = 0
                              AND m.habilitado = 1
                              AND m.cancelado = 0
                            ORDER BY m.nombre_materia ASC";
            $stmtMaterias = $conn->prepare($sqlMaterias);
            if ($stmtMaterias) {
                $stmtMaterias->bind_param('i', $idProfesor);
                $stmtMaterias->execute();
                $materias = $stmtMaterias->get_result()->fetch_all(MYSQLI_ASSOC);
            }

            $carreras = [];
            $sqlCarreras = "SELECT DISTINCT c.id_carrera, c.nombreCarrera AS carrera
                            FROM profesor_materia pm
                            INNER JOIN materia m ON m.id_materia = pm.id_materia
                            INNER JOIN carrera c ON c.id_carrera = m.id_carrera
                            WHERE pm.id_profesor = ?
                              AND pm.habilitado = 1
                              AND pm.cancelado = 0
                              AND m.habilitado = 1
                              AND m.cancelado = 0
                              AND c.habilitado = 1
                              AND c.cancelado = 0
                            ORDER BY c.nombreCarrera ASC";
            $stmtCarreras = $conn->prepare($sqlCarreras);
            if ($stmtCarreras) {
                $stmtCarreras->bind_param('i', $idProfesor);
                $stmtCarreras->execute();
                $carreras = $stmtCarreras->get_result()->fetch_all(MYSQLI_ASSOC);
            }

            $anios = [];
            $sqlAnios = "SELECT DISTINCT ac.id_añoCursada, ac.año
                         FROM usuario u
                         INNER JOIN materia m ON m.id_carrera = u.id_carrera AND m.habilitado = 1 AND m.cancelado = 0
                         INNER JOIN profesor_materia pm ON pm.id_materia = m.id_materia AND pm.id_profesor = ? AND pm.habilitado = 1 AND pm.cancelado = 0
                         INNER JOIN añocursada ac ON ac.id_añoCursada = u.id_añoCursada
                         WHERE u.id_rol = 1
                           AND u.habilitado = 1
                           AND u.cancelado = 0
                           AND ac.habilitado = 1
                           AND ac.cancelado = 0
                         ORDER BY ac.año ASC";
            $stmtAnios = $conn->prepare($sqlAnios);
            if ($stmtAnios) {
                $stmtAnios->bind_param('i', $idProfesor);
                $stmtAnios->execute();
                $anios = $stmtAnios->get_result()->fetch_all(MYSQLI_ASSOC);
            }
        } else {
            // Fallback seguro si la tabla de relación no está disponible.
            $sql = "SELECT
                        u.id_usuario AS id_alumno,
                        c.id_carrera,
                        c.nombreCarrera AS carrera,
                        m.id_materia,
                        m.nombre_materia AS materia,
                        co.comision,
                        ac.id_añoCursada AS id_anio_cursada,
                        ac.año AS anio,
                        p.apellido,
                        u.email,
                        'espera' AS estado_matricula
                    FROM usuario u
                    INNER JOIN persona p
                        ON p.id_persona = u.id_persona
                        AND p.habilitado = 1
                        AND p.cancelado = 0
                    LEFT JOIN carrera c ON c.id_carrera = u.id_carrera
                    LEFT JOIN comision co ON co.id_comision = u.id_comision
                    LEFT JOIN añocursada ac ON ac.id_añoCursada = u.id_añoCursada
                    INNER JOIN materia m ON m.id_carrera = u.id_carrera AND m.habilitado = 1 AND m.cancelado = 0
                    WHERE u.id_rol = 1
                      AND u.habilitado = 1
                      AND u.cancelado = 0
                      AND (? = 0 OR u.id_carrera = ?)
                      AND (? = 0 OR ac.id_añoCursada = ?)
                      AND (? = 0 OR m.id_materia = ?)
                    ORDER BY c.nombreCarrera ASC, m.nombre_materia ASC, ac.año ASC, p.apellido ASC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new \RuntimeException('No se pudo preparar la consulta fallback de perfil profesor: ' . $conn->error);
            }
            $stmt->bind_param(
                'iiiiii',
                $filtroCarrera,
                $filtroCarrera,
                $filtroAnio,
                $filtroAnio,
                $filtroMateria,
                $filtroMateria
            );
            $stmt->execute();
            $result = $stmt->get_result();
            $filas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

            $materias = [];
            if (!empty($usuario['id_carrera'])) {
                $materias = Materia::obtenerPorCarrera($conn, (int)$usuario['id_carrera']);
            }

            $carreras = [];
            if (!empty($usuario['id_carrera'])) {
                $carreraProfesor = Carrera::obtenerPorId($conn, (int)$usuario['id_carrera']);
                if ($carreraProfesor) {
                    $carreras[] = [
                        'id_carrera' => $carreraProfesor['id_carrera'] ?? $usuario['id_carrera'],
                        'carrera' => $carreraProfesor['nombreCarrera'] ?? ''
                    ];
                }
            }

            $anios = [];
            $resAnios = $conn->query("SELECT id_añoCursada, año FROM añocursada WHERE habilitado = 1 AND cancelado = 0 ORDER BY año ASC");
            if ($resAnios) {
                while ($row = $resAnios->fetch_assoc()) {
                    $anios[] = $row;
                }
            }
        }

        return [
            'tipo_perfil' => 'profesor',
            'usuario' => $usuario,
            'persona' => $persona,
            'carrera' => $carrera,
            'inscripciones_tabla' => $filas,
            'filtros' => [
                'id_carrera' => $filtroCarrera,
                'id_materia' => $filtroMateria,
                'id_anio_cursada' => $filtroAnio,
            ],
            'opciones_filtros' => [
                'carreras' => $carreras,
                'materias' => $materias,
                'anios' => $anios,
            ]
        ];
    }

    public static function actualizarEstadoMatricula($conn, $idProfesor, $idAlumno, $idMateria, $esRegular)
    {
        self::asegurarTablasPerfilProfesor($conn);

        $idProfesor = (int)$idProfesor;
        $idAlumno = (int)$idAlumno;
        $idMateria = (int)$idMateria;

        if ($idProfesor <= 0 || $idAlumno <= 0 || $idMateria <= 0) {
            return ['success' => false, 'message' => 'Parámetros inválidos.'];
        }

        $stmtValProf = $conn->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ? AND id_rol = 2 AND habilitado = 1 AND cancelado = 0");
        $stmtValProf->bind_param('i', $idProfesor);
        $stmtValProf->execute();
        if (!$stmtValProf->get_result()->fetch_assoc()) {
            return ['success' => false, 'message' => 'Acción permitida solo para profesores habilitados.'];
        }

        $stmtValAlumno = $conn->prepare("SELECT u.email, p.nombre, p.apellido
                                         FROM usuario u
                                         INNER JOIN persona p ON p.id_persona = u.id_persona
                                         WHERE u.id_usuario = ? AND u.id_rol = 1 AND u.habilitado = 1 AND u.cancelado = 0");
        $stmtValAlumno->bind_param('i', $idAlumno);
        $stmtValAlumno->execute();
        $alumno = $stmtValAlumno->get_result()->fetch_assoc();
        if (!$alumno) {
            return ['success' => false, 'message' => 'Alumno no encontrado o inhabilitado.'];
        }

        $stmtValMateria = $conn->prepare("SELECT nombre_materia FROM materia WHERE id_materia = ? AND habilitado = 1 AND cancelado = 0");
        $stmtValMateria->bind_param('i', $idMateria);
        $stmtValMateria->execute();
        $materia = $stmtValMateria->get_result()->fetch_assoc();
        if (!$materia) {
            return ['success' => false, 'message' => 'Materia no disponible.'];
        }

        if (!self::tablaExiste($conn, 'profesor_materia') || !self::tablaExiste($conn, 'matricula_materia')) {
            return ['success' => false, 'message' => 'Faltan tablas de matrícula. Ejecutá las migraciones de perfil profesor.'];
        }

        $stmtValAsignacion = $conn->prepare("SELECT id_profesor_materia
                                            FROM profesor_materia
                                            WHERE id_profesor = ?
                                              AND id_materia = ?
                                              AND habilitado = 1
                                              AND cancelado = 0
                                            LIMIT 1");
        $stmtValAsignacion->bind_param('ii', $idProfesor, $idMateria);
        $stmtValAsignacion->execute();
        if (!$stmtValAsignacion->get_result()->fetch_assoc()) {
            return ['success' => false, 'message' => 'No tenés asignada esa materia.'];
        }

        $estado = $esRegular ? 'regular' : 'espera';
        $fechaMatricula = $esRegular ? date('Y-m-d H:i:s') : null;

        $sqlUpsert = "INSERT INTO matricula_materia (id_usuario_alumno, id_materia, id_profesor, estado, fecha_matriculacion)
                      VALUES (?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE
                        id_profesor = VALUES(id_profesor),
                        estado = VALUES(estado),
                        fecha_matriculacion = VALUES(fecha_matriculacion),
                        habilitado = 1,
                        cancelado = 0";

        $stmtUpsert = $conn->prepare($sqlUpsert);
        if (!$stmtUpsert) {
            return ['success' => false, 'message' => 'No se pudo guardar el estado de matrícula.'];
        }
        $stmtUpsert->bind_param('iiiss', $idAlumno, $idMateria, $idProfesor, $estado, $fechaMatricula);

        if (!$stmtUpsert->execute()) {
            return ['success' => false, 'message' => 'Error al actualizar la matrícula.'];
        }

        if ($esRegular && !empty($alumno['email'])) {
            $stmtProfesor = $conn->prepare("SELECT p.nombre, p.apellido
                                            FROM usuario u
                                            INNER JOIN persona p ON p.id_persona = u.id_persona
                                            WHERE u.id_usuario = ? LIMIT 1");
            $stmtProfesor->bind_param('i', $idProfesor);
            $stmtProfesor->execute();
            $profesor = $stmtProfesor->get_result()->fetch_assoc() ?: [];

            $nombreProfesor = trim(($profesor['nombre'] ?? '') . ' ' . ($profesor['apellido'] ?? ''));
            $nombreAlumno = trim(($alumno['nombre'] ?? '') . ' ' . ($alumno['apellido'] ?? ''));
            $subject = 'Matriculación confirmada - IFTS15';
            $body = '<p>Hola ' . htmlspecialchars($nombreAlumno ?: 'estudiante') . ',</p>';
            $body .= '<p>Te informamos que fuiste matriculado como <b>alumno regular</b> en la materia <b>' . htmlspecialchars($materia['nombre_materia']) . '</b>.</p>';
            if ($nombreProfesor !== '') {
                $body .= '<p>Profesor responsable: ' . htmlspecialchars($nombreProfesor) . '.</p>';
            }
            $body .= '<p>Saludos,<br>Equipo IFTS15</p>';

            try {
                $mailer = new MailerService();
                $mailer->send($alumno['email'], $subject, $body, true, null);
            } catch (\Throwable $e) {
                error_log('[PerfilService::actualizarEstadoMatricula] Error enviando mail de matriculación: ' . $e->getMessage());
            }
        }

        return [
            'success' => true,
            'message' => $esRegular ? 'Alumno matriculado como regular.' : 'Alumno marcado en espera.',
            'estado' => $estado
        ];
    }
}
