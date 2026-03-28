<?php
/**
 * Vista ABM Carreras y Materias con Drag & Drop
 * IFTS15 - Gestión de Carreras y Materias
 */

require_once __DIR__ . '/../config.php';

use App\Controllers\CarreraController;
use App\Controllers\MateriaController;

// Verificar permisos
$isLoggedIn = isLoggedIn();
$userEmail = $_SESSION['email'] ?? '';
$userRole = isAdminRole() ? 'Administrador' : 'Usuario';

if (!$isLoggedIn || !isAdminRole()) {
    header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
    exit;
}

// Obtener conexión a BD para los componentes
use App\ConectionBD\ConectionDB;
use App\Model\Carrera;
use App\Model\Materia;

$db = new ConectionDB();
$conn = $db->getConnection();

// Obtener datos para los componentes
$carreras = Carrera::obtenerTodas($conn);
$materiasLibres = Materia::obtenerTodas($conn, true, true);

$pageTitle = 'ABM Carreras y Materias - IFTS15';
?>

<?php include __DIR__ . '/../Template/head.php'; ?>
<?php include __DIR__ . '/../Template/navBar.php'; ?>
<?php include __DIR__ . '/../Template/sidebar.php'; ?>

<style>
/* Estilos específicos para drag & drop */
.drag-item {
    cursor: move;
    transition: all 0.2s ease;
    user-select: none;
}

.drag-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.drag-item.dragging {
    opacity: 0.5;
    transform: rotate(3deg);
}

.drop-zone {
    min-height: 100px;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s ease;
}

.drop-zone.drag-over {
    background-color: #e7f1ff;
    border-color: #0d6efd;
    border-style: solid;
}

.materia-item {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 10px 15px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.carrera-card {
    min-height: 200px;
}

.btn-sm-icon {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

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

/**
 * Recargar lista de materias libres desde el servidor
 */
function recargarMaterias() {
    fetch('<?= BASE_URL ?>/src/Controllers/materiaController.php?action=listar&libres=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('materias-libres-container');
            container.innerHTML = '';
            
            data.materias.forEach(m => {
                const div = document.createElement('div');
                div.className = 'materia-item';
                div.dataset.id = m.id_materia;
                div.innerHTML = `
                    <span>${m.nombre_materia}</span>
                    <div>
                        <button class="btn btn-sm btn-sm-icon btn-outline-primary me-1 btn-editar-materia" data-id="${m.id_materia}" data-nombre="${m.nombre_materia}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-sm-icon btn-outline-danger btn-eliminar-materia" data-id="${m.id_materia}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                container.appendChild(div);
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
    location.reload(); // Recarga completa para simplicidad
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
