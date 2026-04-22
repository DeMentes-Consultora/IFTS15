<?php
/**
 * Vista ABM Profesor-Materia (roles 3 y 5)
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/ProfesorMateria.php';

$conectarDB = new App\ConectionBD\ConectionDB();
$conn = $conectarDB->getConnection();

$isLoggedIn = isLoggedIn();
$idRol = (int)($_SESSION['id_rol'] ?? $_SESSION['role_id'] ?? 0);
if (!$isLoggedIn || !in_array($idRol, [3, 5], true)) {
    header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
    exit;
}

App\Model\ProfesorMateria::asegurarTabla($conn);

$profesores = App\Model\ProfesorMateria::obtenerProfesores($conn);
$carreras = App\Model\ProfesorMateria::obtenerCarreras($conn);

$idProfesor = (int)($_GET['id_profesor'] ?? 0);
$idCarrera = (int)($_GET['id_carrera'] ?? 0);
$idMateria = (int)($_GET['id_materia'] ?? 0);

$tablaAsignaciones = [];
if ($idProfesor > 0) {
    $tablaAsignaciones = App\Model\ProfesorMateria::obtenerTablaAsignacion($conn, $idProfesor, $idCarrera, $idMateria);
}

$pageTitle = 'ABM Profesor-Materia - IFTS15';
?>

<?php include __DIR__ . '/../Template/head.php'; ?>
<?php include __DIR__ . '/../Template/navBar.php'; ?>
<?php include __DIR__ . '/../Template/sidebar.php'; ?>
<link rel="stylesheet" href="../Css/abm-profesores-materias.css">

<div class="container mt-5 pt-4">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="bi bi-person-workspace me-2"></i>
                ABM Asignación Profesor-Materia
            </h2>
            <small class="text-muted">Gestión para roles Administrativo (3) y Administrador (5)</small>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong><i class="bi bi-funnel me-2"></i>Filtros</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= htmlspecialchars(BASE_URL) ?>/src/Controllers/viewController.php" class="row g-3 align-items-end">
                <input type="hidden" name="view" value="abm-profesores-materias">

                <div class="col-md-4">
                    <label for="id_profesor" class="form-label">Profesor</label>
                    <select class="form-select" id="id_profesor" name="id_profesor" required>
                        <option value="0">Seleccionar profesor</option>
                        <?php foreach ($profesores as $profesor): ?>
                            <option value="<?= (int)$profesor['id_usuario'] ?>" <?= $idProfesor === (int)$profesor['id_usuario'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars(($profesor['apellido'] ?? '') . ', ' . ($profesor['nombre'] ?? '') . ' - ' . ($profesor['email'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="id_carrera" class="form-label">Carrera</label>
                    <select class="form-select" id="id_carrera" name="id_carrera">
                        <option value="0">Todas</option>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= (int)$carrera['id_carrera'] ?>" <?= $idCarrera === (int)$carrera['id_carrera'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($carrera['carrera'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="id_materia" class="form-label">ID Materia</label>
                    <input type="number" min="0" class="form-control" id="id_materia" name="id_materia" value="<?= $idMateria > 0 ? (int)$idMateria : '' ?>" placeholder="Opcional">
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <strong><i class="bi bi-table me-2"></i>Asignaciones</strong>
            <small class="text-muted">Tildar para asignar, destildar para quitar</small>
        </div>
        <div class="card-body">
            <div id="feedback-asignacion" class="small mb-3" style="display:none;"></div>

            <?php if ($idProfesor <= 0): ?>
                <div class="alert alert-info mb-0">Seleccioná un profesor para gestionar sus materias.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-warning">
                            <tr>
                                <th>N°</th>
                                <th>Carrera</th>
                                <th>Materia</th>
                                <th>ID Materia</th>
                                <th>Asignada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tablaAsignaciones)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay materias para los filtros seleccionados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tablaAsignaciones as $i => $fila): ?>
                                    <?php $checked = ((int)($fila['asignada'] ?? 0) === 1); ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($fila['carrera'] ?? 'Sin carrera') ?></td>
                                        <td class="text-start"><?= htmlspecialchars($fila['nombre_materia'] ?? '') ?></td>
                                        <td><?= (int)($fila['id_materia'] ?? 0) ?></td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center mb-0">
                                                <input
                                                    class="form-check-input check-asignacion-profesor"
                                                    type="checkbox"
                                                    <?= $checked ? 'checked' : '' ?>
                                                    data-id-profesor="<?= (int)$idProfesor ?>"
                                                    data-id-materia="<?= (int)($fila['id_materia'] ?? 0) ?>">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function() {
    var checks = document.querySelectorAll('.check-asignacion-profesor');
    var feedback = document.getElementById('feedback-asignacion');

    function showFeedback(text, type) {
        feedback.className = 'small mb-3 ' + (type === 'ok' ? 'text-success' : 'text-danger');
        feedback.textContent = text;
        feedback.style.display = 'block';
    }

    checks.forEach(function(check) {
        check.addEventListener('change', function() {
            var idProfesor = parseInt(this.dataset.idProfesor || '0', 10);
            var idMateria = parseInt(this.dataset.idMateria || '0', 10);
            var asignada = this.checked ? 1 : 0;
            var input = this;

            input.disabled = true;

            fetch('../Controllers/profesorMateriaController.php?action=toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: 'id_profesor=' + encodeURIComponent(idProfesor)
                    + '&id_materia=' + encodeURIComponent(idMateria)
                    + '&asignada=' + encodeURIComponent(asignada)
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data || !data.success) {
                    input.checked = !input.checked;
                    showFeedback((data && data.error) ? data.error : 'No se pudo guardar.', 'error');
                    return;
                }
                showFeedback(data.message || 'Asignación actualizada.', 'ok');
            })
            .catch(function() {
                input.checked = !input.checked;
                showFeedback('Error de red al actualizar la asignación.', 'error');
            })
            .finally(function() {
                input.disabled = false;
            });
        });
    });
})();
</script>

<?php include __DIR__ . '/../Template/footer.php'; ?>
