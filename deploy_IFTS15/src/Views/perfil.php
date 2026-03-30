<?php
// ...existing code...
$esPerfilUsuario = true;
require_once __DIR__ . '/../config.php';
$conectarDB = new App\ConectionBD\ConectionDB();
$conn = $conectarDB->getConnection();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userEmail = $_SESSION['email'] ?? '';
$userRole = isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 5 ? 'Administrador' : 'Usuario';
if (!$isLoggedIn) {
    header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
    exit;
}
include __DIR__ . '/../Template/head.php';
include __DIR__ . '/../Template/navBar.php';
include __DIR__ . '/../Template/sidebar.php';
// src/Views/perfil.php
// Vista del perfil de usuario (alumno)

// Espera recibir $datosPerfil (usuario, persona, carrera, notas)
$usuario = $datosPerfil['usuario'] ?? [];
$persona = $datosPerfil['persona'] ?? null;
$carrera = $datosPerfil['carrera'] ?? [];
$notas = $datosPerfil['notas'] ?? [];

// Mostrar errores si existen
if (isset($error)) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
}
?>

<div class="container mt-4 fade-in">
    <div class="row justify-content-center">
        <!-- Columna lateral: Foto y datos personales -->
        <div class="col-lg-4 mb-4">
            <div class="card card-welcome text-center h-100">
                <div class="card-body">
                    <?php if ($persona && !empty($persona->getFotoPerfilUrl())): ?>
                        <img src="<?= htmlspecialchars($persona->getFotoPerfilUrl()) ?>" alt="Foto de perfil" class="rounded-circle mb-3 border border-3 border-warning" style="width:120px;height:120px;object-fit:cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle mb-3" style="font-size: 6rem; color: var(--primary-color);"></i>
                    <?php endif; ?>
                    <h4 class="fw-bold mb-1 text-primary"><?= htmlspecialchars($persona ? $persona->getNombre() : '') . ' ' . htmlspecialchars($persona ? $persona->getApellido() : '') ?></h4>
                    <p class="mb-1"><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                    <span class="badge bg-primary text-dark mb-2">Alumno</span>
                    <hr>
                    <h6 class="text-warning mb-3">Datos personales</h6>
                    <ul class="list-group list-group-flush text-start mb-0">
                        <li class="list-group-item bg-transparent px-0 py-1"><strong>DNI:</strong> <?= htmlspecialchars($persona ? $persona->getDni() : '') ?></li>
                        <li class="list-group-item bg-transparent px-0 py-1"><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars($persona ? $persona->getFechaNacimiento() : '') ?></li>
                        <li class="list-group-item bg-transparent px-0 py-1"><strong>Edad:</strong> <?= htmlspecialchars($persona ? $persona->getEdadBD() : '') ?></li>
                        <li class="list-group-item bg-transparent px-0 py-1"><strong>Teléfono:</strong> <?= htmlspecialchars($persona ? $persona->getTelefono() : '') ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Columna principal: Datos académicos y notas -->
        <div class="col-lg-8 mb-4">
            <div class="card mb-4 slide-in-left">
                <div class="card-header bg-primary text-dark">
                    <i class="bi bi-mortarboard me-2"></i> Datos académicos
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-0">
                        <li class="list-group-item bg-transparent px-0 py-1"><strong>Carrera:</strong> <?= htmlspecialchars($carrera['nombreCarrera'] ?? 'No asignada') ?></li>
                        <li class="list-group-item bg-transparent px-0 py-1"><strong>Comisión:</strong> <?= htmlspecialchars($usuario['id_comision'] ?? 'No asignada') ?></li>
                        <li class="list-group-item bg-transparent px-0 py-1"><strong>Año de cursada:</strong> <?= htmlspecialchars($usuario['id_añoCursada'] ?? 'No asignado') ?></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-primary text-dark">
                    <i class="bi bi-book me-2"></i> Materias y notas
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-warning">
                                <tr>
                                    <th>Materia</th>
                                    <th>Nota</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($notas)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay notas registradas.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($notas as $nota): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($nota['nombre_materia'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($nota['nota'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($nota['fecha'] ?? ($nota['idCreate'] ?? '')) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../Template/footer.php'; ?>