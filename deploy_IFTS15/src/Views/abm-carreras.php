<?php
/**
 * Vista ABM Carreras y Materias con Drag & Drop
 * IFTS15 - Gestión de Carreras y Materias
 */

require_once __DIR__ . '/../config.php';
$conectarDB = new App\ConectionBD\ConectionDB();
$conn = $conectarDB->getConnection();

$materiasLibres = App\Model\Materia::obtenerTodas($conn, true, true);
$carreras = App\Model\Carrera::obtenerTodas($conn, true);

// Verificar permisos
$isLoggedIn = isLoggedIn();
$userEmail = $_SESSION['email'] ?? '';
$userRole = isAdminRole() ? 'Administrador' : 'Usuario';
if (!$isLoggedIn || !isAdminRole()) {
    header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
    exit;
}
// ...existing code...
$pageTitle = 'ABM Carreras y Materias - IFTS15';
?>

<?php include __DIR__ . '/../Template/head.php'; ?>
<?php include __DIR__ . '/../Template/navBar.php'; ?>

<?php include __DIR__ . '/../Template/sidebar.php'; ?>
<link rel="stylesheet" href="../Css/abm-carreras.css">

<script>
// IMPORTANTE: Definir funciones globales ANTES de incluir componentes
// Helper global para mostrar toasts Bootstrap 5
function showToast(message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'danger' ? 'danger' : (type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'secondary'));
    
    const toastEl = document.createElement('div');
    toastEl.className = 'toast align-items-center text-bg-' + bgClass + ' border-0';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.id = toastId;

    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    container.appendChild(toastEl);
    const bsToast = new bootstrap.Toast(toastEl, { delay: duration });
    bsToast.show();

    toastEl.addEventListener('hidden.bs.toast', function() {
        toastEl.remove();
    });
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/\"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function renderMateriaLibre(materia) {
    return `
        <div class="materia-item"
            data-id="${materia.id_materia}"
            data-id-materia="${materia.id_materia}">
            <span>${escapeHtml(materia.nombre_materia)}</span>
            <div>
                <button class="btn btn-sm btn-sm-icon btn-outline-primary me-1 btn-editar-materia" data-id="${materia.id_materia}" data-nombre="${escapeHtml(materia.nombre_materia)}" title="Editar">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-sm-icon btn-outline-danger btn-eliminar-materia" data-id="${materia.id_materia}" title="Eliminar">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
}

function renderMateriaAsociada(carreraId, materia) {
    return `
        <div class="materia-item"
            data-id="${materia.id_materia}"
            data-id-materia="${materia.id_materia}">
            <span>${escapeHtml(materia.nombre_materia)}</span>
            <button class="btn btn-sm btn-sm-icon btn-outline-danger btn-desasociar-materia"
                    data-id-carrera="${carreraId}"
                    data-id-materia="${materia.id_materia}"
                    title="Quitar de la carrera">
                <i class="bi bi-x-circle"></i>
            </button>
        </div>
    `;
}

function renderCarreraCard(carrera) {
    const materias = Array.isArray(carrera.materias) ? carrera.materias : [];
    const materiasHtml = materias.length === 0
        ? `
            <p class="text-muted text-center mb-0 placeholder-drop-zone">
                <small>
                    <i class="bi bi-arrow-down-circle"></i><br>
                    Arrastra materias aquí
                </small>
            </p>
        `
        : materias.map(materia => renderMateriaAsociada(carrera.id_carrera, materia)).join('');

    return `
        <div class="card carrera-card mb-3" data-id-carrera="${carrera.id_carrera}">
            <div class="card-header bg-light d-flex justify-content-between align-items-center"
                 style="cursor: pointer;"
                 onclick="toggleCarrera(${carrera.id_carrera})">
                <div class="d-flex align-items-center">
                    <i class="bi bi-chevron-down me-2 collapse-icon" id="icon-${carrera.id_carrera}"></i>
                    <strong>${escapeHtml(carrera.carrera)}</strong>
                    <small class="text-muted ms-2">(${materias.length} materias)</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-sm-icon btn-outline-primary me-1 btn-editar-carrera"
                            data-id="${carrera.id_carrera}"
                            data-nombre="${escapeHtml(carrera.carrera)}"
                            title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-sm-icon btn-outline-danger btn-eliminar-carrera"
                            data-id="${carrera.id_carrera}"
                            title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body collapse-content" id="body-${carrera.id_carrera}">
                <div class="drop-zone carrera-drop-zone" data-id-carrera="${carrera.id_carrera}">
                    ${materiasHtml}
                </div>
            </div>
        </div>
    `;
}

/**
 * Recargar lista de materias libres desde el servidor
 */
function recargarMaterias() {
    return fetch('<?= BASE_URL ?>/src/Controllers/materiaController.php?action=listar&libres=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('materias-libres-container');
            container.innerHTML = '';

            if (!Array.isArray(data.materias) || data.materias.length === 0) {
                container.innerHTML = `
                    <p class="text-muted text-center py-3">
                        <i class="bi bi-inbox"></i><br>
                        No hay materias disponibles
                    </p>
                `;
                if (typeof initDragMaterias === 'function') {
                    initDragMaterias();
                }
                return;
            }
            
            data.materias.forEach(m => {
                container.insertAdjacentHTML('beforeend', renderMateriaLibre(m));
            });
            
            // Reinicializar Sortable para los nuevos elementos
            if (typeof initDragMaterias === 'function') {
                initDragMaterias();
            } else {
                console.error('Error: initDragMaterias no está definida');
            }
        }
    })
    .catch(err => console.error('Error recargando materias:', err));
}

function recargarCarreras() {
    return fetch('<?= BASE_URL ?>/src/Controllers/carreraController.php?action=listar', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.error || 'No se pudieron recargar las carreras');
        }

        const container = document.getElementById('carreras-container');
        if (!container) {
            return;
        }

        if (!Array.isArray(data.carreras) || data.carreras.length === 0) {
            container.innerHTML = `
                <p class="text-muted text-center py-3">
                    <i class="bi bi-inbox"></i><br>
                    No hay carreras registradas
                </p>
            `;
            return;
        }

        container.innerHTML = data.carreras.map(renderCarreraCard).join('');

        if (typeof initDropZones === 'function') {
            initDropZones();
        }
    })
    .catch(err => {
        console.error('Error recargando carreras:', err);
        showToast('No se pudieron actualizar las carreras', 'danger');
    });
}
</script>

<div class="container mt-5 pt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="bi bi-mortarboard me-2"></i>
                Gestión de Carreras y Materias
            </h2>
        </div>
    </div>

    <div class="row">
        <!-- Panel Izquierdo: Materias -->
        <div class="col-md-6 mb-4">
            <?php include __DIR__ . '/../Components/listaMaterias.php'; ?>
        </div>

        <!-- Panel Derecho: Carreras -->
        <div class="col-md-6 mb-4">
            <?php include __DIR__ . '/../Components/listaCarreras.php'; ?>
        </div>
    </div>
</div>

<!-- Contenedor para toasts -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;"></div>
</div>

<script>
// Helper para peticiones AJAX
async function fetchAPI(url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                ...options.headers
            }
        });
        
        if (!response.ok) {
            const error = await response.json().catch(() => ({ error: 'Error de red' }));
            throw new Error(error.error || 'Error en la petición');
        }
        
        return await response.json();
    } catch (err) {
        console.error('fetchAPI error:', err);
        throw err;
    }
}
</script>

<?php include __DIR__ . '/../Template/footer.php'; ?>
