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
$materiasPerfil = $datosPerfil['materias_perfil'] ?? [];

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
                    <div style="position: relative; display: inline-block;">
                        <?php if ($persona && !empty($persona->getFotoPerfilUrl())): ?>
                            <img id="foto-perfil-img" src="<?= htmlspecialchars($persona->getFotoPerfilUrl()) ?>" alt="Foto de perfil" class="rounded-circle mb-3 border border-3 border-warning" style="width:120px;height:120px;object-fit:cover;">
                        <?php else: ?>
                            <i id="foto-perfil-img" class="bi bi-person-circle mb-3" style="font-size: 6rem; color: var(--primary-color);"></i>
                        <?php endif; ?>
                        <!-- Icono lápiz -->
                        <button id="editar-foto-btn" type="button" class="btn btn-sm btn-light border border-2 border-warning position-absolute" style="right: 0; bottom: 10px; z-index: 2; border-radius: 50%;" title="Cambiar foto de perfil">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <!-- Card para subir nueva foto (oculta por defecto) -->
                    <div id="card-cambiar-foto" class="card mt-2" style="display:none;">
                        <div class="card-body">
                            <form id="form-cambiar-foto" enctype="multipart/form-data">
                                <div class="mb-2">
                                    <label for="nueva_foto" class="form-label">Nueva foto de perfil</label>
                                    <input class="form-control" type="file" id="nueva_foto" name="nueva_foto" accept="image/*" required>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="cancelar-cambiar-foto" class="btn btn-secondary btn-sm me-2">Cancelar</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Subir</button>
                                </div>
                                <div id="foto-error-msg" class="text-danger mt-2" style="display:none;"></div>
                            </form>
                        </div>
                    </div>
                    <script>
                    // Mostrar card al hacer click en el lápiz
                    document.getElementById('editar-foto-btn').addEventListener('click', function() {
                        document.getElementById('card-cambiar-foto').style.display = 'block';
                    });

                    // Ocultar card al cancelar
                    document.getElementById('cancelar-cambiar-foto').addEventListener('click', function() {
                        document.getElementById('card-cambiar-foto').style.display = 'none';
                        document.getElementById('form-cambiar-foto').reset();
                        document.getElementById('foto-error-msg').style.display = 'none';
                    });

                    // Subida AJAX de la foto
                    document.getElementById('form-cambiar-foto').addEventListener('submit', function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        fetch('../Controllers/perfilController.php?action=cambiar_foto', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Actualizar imagen de perfil en ambos lugares (perfil y navbar) con cache busting
                                document.getElementById('foto-perfil-img').src = data.nueva_foto_url + '?t=' + new Date().getTime();
                                // Actualizar avatar del navbar si existe
                                var navAvatar = document.querySelector('.navbar img[alt="Avatar"]');
                                if (navAvatar) {
                                    navAvatar.src = data.nueva_foto_url + '?t=' + new Date().getTime();
                                }
                                document.getElementById('card-cambiar-foto').style.display = 'none';
                                document.getElementById('form-cambiar-foto').reset();
                                document.getElementById('foto-error-msg').style.display = 'none';
                            } else {
                                document.getElementById('foto-error-msg').textContent = data.error || 'Error al subir la foto.';
                                document.getElementById('foto-error-msg').style.display = 'block';
                            }
                        })
                        .catch(() => {
                            document.getElementById('foto-error-msg').textContent = 'Error al subir la foto.';
                            document.getElementById('foto-error-msg').style.display = 'block';
                        });
                    });
                    </script>
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
                                <?php if (empty($materiasPerfil)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay materias asociadas a tu carrera.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($materiasPerfil as $materia): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($materia['nombre_materia'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($materia['nota'] ?? 'Sin nota') ?></td>
                                            <td><?= htmlspecialchars($materia['fecha_nota'] ?? '-') ?></td>
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