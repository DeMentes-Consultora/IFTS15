<?php

require_once __DIR__ . '/../config.php';

$hasBolsaAccess = function_exists('canAccessBolsaTrabajo') ? canAccessBolsaTrabajo() : false;
$canManageBolsa = function_exists('canManageBolsaTrabajo') ? canManageBolsaTrabajo() : false;

$parseIniSizeToBytes = static function (?string $value): int {
    $value = trim((string)$value);
    if ($value === '') {
        return 0;
    }

    $unit = strtolower(substr($value, -1));
    $number = (float)$value;

    switch ($unit) {
        case 'g':
            return (int)($number * 1024 * 1024 * 1024);
        case 'm':
            return (int)($number * 1024 * 1024);
        case 'k':
            return (int)($number * 1024);
        default:
            return (int)$number;
    }
};

$functionalCvMaxBytes = 5 * 1024 * 1024;
$uploadMaxBytes = $parseIniSizeToBytes(ini_get('upload_max_filesize'));
$postMaxBytes = $parseIniSizeToBytes(ini_get('post_max_size'));
$serverCvMaxBytes = min(
    $functionalCvMaxBytes,
    $uploadMaxBytes > 0 ? $uploadMaxBytes : $functionalCvMaxBytes,
    $postMaxBytes > 0 ? $postMaxBytes : $functionalCvMaxBytes
);
$serverCvMaxLabel = number_format($serverCvMaxBytes / 1024 / 1024, 2, ',', '.') . ' MB';

if (!isLoggedIn() || !$hasBolsaAccess) {
    header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
    exit;
}

$conn = getConnection();
$isLoggedIn = isLoggedIn();
$userRoleId = getUserRoleId();
$canManage = $canManageBolsa;
$isAlumno = $userRoleId === 1;
$pageTitle = $canManage ? 'Gestion de Bolsa de Trabajo' : 'Bolsa de Trabajo';

include __DIR__ . '/../Template/head.php';
include __DIR__ . '/../Template/navBar.php';
include __DIR__ . '/../Template/sidebar.php';
?>

<div class="container py-5 mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1"><?php echo $canManage ? 'Gestion de Bolsa de Trabajo' : 'Bolsa de Trabajo'; ?></h1>
            <p class="text-muted mb-0">
                <?php echo $canManage
                    ? 'Los roles Administrativo y Administrador pueden crear, publicar, rechazar y pausar ofertas laborales.'
                    : 'Aqui se muestran las ofertas laborales publicadas para alumnos.'; ?>
            </p>
        </div>
        <?php if ($canManage): ?>
            <div class="d-flex gap-2 flex-wrap" id="bolsa-resumen-cards">
                <div class="card border-0 shadow-sm"><div class="card-body py-2 px-3"><small class="text-muted d-block">Pendientes</small><strong id="resumen-pendientes">0</strong></div></div>
                <div class="card border-0 shadow-sm"><div class="card-body py-2 px-3"><small class="text-muted d-block">Publicadas</small><strong id="resumen-publicadas">0</strong></div></div>
                <div class="card border-0 shadow-sm"><div class="card-body py-2 px-3"><small class="text-muted d-block">Rechazadas</small><strong id="resumen-rechazadas">0</strong></div></div>
            </div>
        <?php endif; ?>
    </div>

    <div id="bolsa-alertas"></div>

    <?php if ($canManage): ?>
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-warning text-dark">
                        <strong><i class="bi bi-briefcase me-2"></i>Nueva oferta laboral</strong>
                    </div>
                    <div class="card-body">
                        <form id="form-crear-oferta">
                            <div class="mb-3">
                                <label for="titulo-oferta" class="form-label">Titulo</label>
                                <input type="text" class="form-control" id="titulo-oferta" name="titulo" maxlength="255" required>
                            </div>
                            <div class="mb-3">
                                <label for="texto-oferta" class="form-label">Descripcion</label>
                                <textarea class="form-control" id="texto-oferta" name="texto" rows="7" required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-send me-1"></i>Enviar a revision
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <strong><i class="bi bi-list-task me-2"></i>Gestion de ofertas</strong>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="bolsaTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes-pane" type="button" role="tab">Pendientes</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="publicadas-tab" data-bs-toggle="tab" data-bs-target="#publicadas-pane" type="button" role="tab">Publicadas</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pendientes-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Titulo</th>
                                                <th>Creador</th>
                                                <th>Fecha</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla-pendientes"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="publicadas-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Titulo</th>
                                                <th>Contacto</th>
                                                <th>Fecha</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla-publicadas-admin"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($isAlumno): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <strong><i class="bi bi-person-workspace me-2"></i>Mis postulaciones</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Oferta</th>
                                <th>Contacto</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-mis-postulaciones"></tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong><i class="bi bi-megaphone me-2"></i>Ofertas publicadas</strong>
        </div>
        <div class="card-body">
            <div class="row g-3" id="ofertas-publicadas-grid"></div>
        </div>
    </div>
</div>

<?php if ($isAlumno): ?>
    <div class="modal fade" id="modalPostulacionBolsa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5">Postularme a la oferta</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="form-postularse-bolsa">
                    <div class="modal-body">
                        <input type="hidden" name="id_bolsa_trabajo" id="postulacion-id-oferta">
                        <p class="mb-3">Vas a postularte a: <strong id="postulacion-titulo-oferta"></strong></p>
                        <div class="mb-3">
                            <label for="postulacion-cv" class="form-label">CV en PDF, DOC o DOCX</label>
                            <input type="file" class="form-control" id="postulacion-cv" name="cv" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                            <div class="form-text">Tamaño maximo en este entorno: <?php echo htmlspecialchars($serverCvMaxLabel, ENT_QUOTES, 'UTF-8'); ?>.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar postulacion</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
const baseUrl = '<?php echo BASE_URL; ?>';
const bolsaControllerUrl = baseUrl + '/src/Controllers/bolsaTrabajoController.php';
const canManageBolsa = <?php echo $canManage ? 'true' : 'false'; ?>;
const isAlumno = <?php echo $isAlumno ? 'true' : 'false'; ?>;
const bolsaCvMaxBytes = <?php echo (int)$serverCvMaxBytes; ?>;
const bolsaCvMaxLabel = '<?php echo addslashes($serverCvMaxLabel); ?>';
const bolsaCvAllowedExtensions = ['pdf', 'doc', 'docx'];

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function showBolsaAlert(message, type = 'success') {
    const container = document.getElementById('bolsa-alertas');
    if (!container) return;

    container.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}

function blurActiveElement() {
    if (document.activeElement && typeof document.activeElement.blur === 'function') {
        document.activeElement.blur();
    }
}

function getFileExtension(fileName) {
    const parts = String(fileName || '').toLowerCase().split('.');
    return parts.length > 1 ? parts.pop() : '';
}

function formatDate(value) {
    if (!value) return '-';
    const date = new Date(value.replace(' ', 'T'));
    return Number.isNaN(date.getTime()) ? value : date.toLocaleString('es-AR');
}

function renderPublishedCards(ofertas) {
    const container = document.getElementById('ofertas-publicadas-grid');
    if (!container) return;

    if (!Array.isArray(ofertas) || ofertas.length === 0) {
        container.innerHTML = '<div class="col-12"><p class="text-muted mb-0">No hay ofertas publicadas por el momento.</p></div>';
        return;
    }

    container.innerHTML = ofertas.map(oferta => `
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${escapeHtml(oferta.titulo_oferta)}</h5>
                    <p class="card-text text-muted small mb-2">
                        Publicada por ${escapeHtml((oferta.nombre || '') + ' ' + (oferta.apellido || ''))}
                    </p>
                    <p class="card-text flex-grow-1">${escapeHtml(oferta.texto_oferta)}</p>
                    <div class="border-top pt-3 mt-3 small text-muted">
                        <div><i class="bi bi-envelope me-1"></i>${escapeHtml(oferta.email || '-')}</div>
                        <div><i class="bi bi-telephone me-1"></i>${escapeHtml(oferta.telefono || '-')}</div>
                        <div><i class="bi bi-calendar-event me-1"></i>${escapeHtml(formatDate(oferta.fecha_creacion))}</div>
                        ${oferta.carrera ? `<div><i class="bi bi-mortarboard me-1"></i>${escapeHtml(oferta.carrera)}</div>` : ''}
                    </div>
                    ${isAlumno ? `
                        <div class="mt-3 d-grid gap-2">
                            <button
                                class="btn ${oferta.ya_postulado ? 'btn-outline-success' : 'btn-primary'} btn-postular-oferta"
                                data-id="${oferta.id_bolsa_trabajo}"
                                data-titulo="${escapeHtml(oferta.titulo_oferta)}"
                                ${oferta.ya_postulado ? 'disabled' : ''}
                            >
                                ${oferta.ya_postulado ? 'Ya postulado' : 'Postularme'}
                            </button>
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

function renderMyPostulations(postulaciones) {
    const tbody = document.getElementById('tabla-mis-postulaciones');
    if (!tbody) return;

    if (!Array.isArray(postulaciones) || postulaciones.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-muted text-center py-4">Todavia no te postulaste a ninguna oferta.</td></tr>';
        return;
    }

    tbody.innerHTML = postulaciones.map(postulacion => `
        <tr>
            <td>
                <strong>${escapeHtml(postulacion.titulo_oferta)}</strong>
                <div class="small text-muted">${escapeHtml(postulacion.texto_oferta)}</div>
            </td>
            <td>
                <div>${escapeHtml((postulacion.nombre || '') + ' ' + (postulacion.apellido || ''))}</div>
                <div class="small text-muted">${escapeHtml(postulacion.email || '-')}</div>
            </td>
            <td>${escapeHtml(formatDate(postulacion.fecha_postulacion))}</td>
            <td class="text-end">
                ${postulacion.cv_url ? `<a class="btn btn-sm btn-outline-secondary me-1" href="${escapeHtml(postulacion.cv_url)}" target="_blank" rel="noopener noreferrer">Ver CV</a>` : ''}
                <button class="btn btn-sm btn-outline-danger btn-cancelar-postulacion" data-id="${postulacion.id_postulacion_bolsa_trabajo}">Cancelar</button>
            </td>
        </tr>
    `).join('');
}

function renderPendingTable(ofertas) {
    const tbody = document.getElementById('tabla-pendientes');
    if (!tbody) return;

    if (!Array.isArray(ofertas) || ofertas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-muted text-center py-4">No hay ofertas pendientes.</td></tr>';
        return;
    }

    tbody.innerHTML = ofertas.map(oferta => `
        <tr>
            <td>
                <strong>${escapeHtml(oferta.titulo_oferta)}</strong>
                <div class="small text-muted">${escapeHtml(oferta.texto_oferta)}</div>
            </td>
            <td>${escapeHtml((oferta.nombre || '') + ' ' + (oferta.apellido || ''))}</td>
            <td>${escapeHtml(formatDate(oferta.fecha_creacion))}</td>
            <td class="text-end">
                <button class="btn btn-sm btn-success me-1" data-accion="publicar" data-id="${oferta.id_bolsa_trabajo}">Publicar</button>
                <button class="btn btn-sm btn-outline-danger" data-accion="rechazar" data-id="${oferta.id_bolsa_trabajo}">Rechazar</button>
            </td>
        </tr>
    `).join('');
}

function renderPublishedAdminTable(ofertas) {
    const tbody = document.getElementById('tabla-publicadas-admin');
    if (!tbody) return;

    if (!Array.isArray(ofertas) || ofertas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-muted text-center py-4">No hay ofertas publicadas.</td></tr>';
        return;
    }

    tbody.innerHTML = ofertas.map(oferta => `
        <tr>
            <td>
                <strong>${escapeHtml(oferta.titulo_oferta)}</strong>
                <div class="small text-muted">${escapeHtml(oferta.texto_oferta)}</div>
            </td>
            <td>
                <div>${escapeHtml(oferta.email || '-')}</div>
                <div class="small text-muted">${escapeHtml(oferta.telefono || '-')}</div>
            </td>
            <td>${escapeHtml(formatDate(oferta.fecha_creacion))}</td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-warning" data-accion="deshabilitar" data-id="${oferta.id_bolsa_trabajo}">Enviar a pendientes</button>
            </td>
        </tr>
    `).join('');
}

async function fetchJson(url, options = {}) {
    const response = await fetch(url, options);
    const data = await response.json();
    if (!response.ok || !data.success) {
        throw new Error(data.error || data.message || 'Ocurrio un error');
    }
    return data;
}

async function cargarPublicadas() {
    const data = await fetchJson(bolsaControllerUrl + '?action=listar-publicadas');
    renderPublishedCards(data.ofertas || []);
    if (canManageBolsa) {
        renderPublishedAdminTable(data.ofertas || []);
    }
}

async function cargarPendientes() {
    if (!canManageBolsa) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=listar-pendientes');
    renderPendingTable(data.ofertas || []);
}

async function cargarResumen() {
    if (!canManageBolsa) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=resumen');
    document.getElementById('resumen-pendientes').textContent = data.resumen.pendientes;
    document.getElementById('resumen-publicadas').textContent = data.resumen.publicadas;
    document.getElementById('resumen-rechazadas').textContent = data.resumen.rechazadas;
}

async function cargarMisPostulaciones() {
    if (!isAlumno) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=listar-mis-postulaciones');
    renderMyPostulations(data.postulaciones || []);
}

async function recargarBolsa() {
    await cargarPublicadas();
    if (canManageBolsa) {
        await Promise.all([cargarPendientes(), cargarResumen()]);
    }
    if (isAlumno) {
        await cargarMisPostulaciones();
    }
}

document.addEventListener('submit', async function (event) {
    if (event.target.id !== 'form-crear-oferta') return;

    event.preventDefault();
    const formData = new FormData(event.target);
    formData.append('action', 'crear');

    try {
        const data = await fetchJson(bolsaControllerUrl, {
            method: 'POST',
            body: formData
        });
        event.target.reset();
        showBolsaAlert(data.message, 'success');
        await recargarBolsa();
    } catch (error) {
        showBolsaAlert(error.message, 'danger');
    }
});

document.addEventListener('click', async function (event) {
    const postularButton = event.target.closest('.btn-postular-oferta[data-id]');
    if (postularButton) {
        const inputId = document.getElementById('postulacion-id-oferta');
        const inputTitulo = document.getElementById('postulacion-titulo-oferta');
        const inputCv = document.getElementById('postulacion-cv');
        if (inputId) inputId.value = postularButton.dataset.id;
        if (inputTitulo) inputTitulo.textContent = postularButton.dataset.titulo || '';
        if (inputCv) inputCv.value = '';

        const modalElement = document.getElementById('modalPostulacionBolsa');
        if (modalElement && window.bootstrap && window.bootstrap.Modal) {
            blurActiveElement();
            window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }
        return;
    }

    const cancelarButton = event.target.closest('.btn-cancelar-postulacion[data-id]');
    if (cancelarButton) {
        const formData = new FormData();
        formData.append('action', 'cancelar-postulacion');
        formData.append('id_postulacion_bolsa_trabajo', cancelarButton.dataset.id);

        try {
            const data = await fetchJson(bolsaControllerUrl, {
                method: 'POST',
                body: formData
            });
            showBolsaAlert(data.message, 'success');
            await recargarBolsa();
        } catch (error) {
            showBolsaAlert(error.message, 'danger');
        }
        return;
    }

    const button = event.target.closest('[data-accion][data-id]');
    if (!button) return;

    const formData = new FormData();
    formData.append('action', 'gestionar');
    formData.append('id_bolsa_trabajo', button.dataset.id);
    formData.append('accion', button.dataset.accion);

    try {
        const data = await fetchJson(bolsaControllerUrl, {
            method: 'POST',
            body: formData
        });
        showBolsaAlert(data.message, 'success');
        await recargarBolsa();
    } catch (error) {
        showBolsaAlert(error.message, 'danger');
    }
});

document.addEventListener('submit', async function (event) {
    if (event.target.id !== 'form-postularse-bolsa') return;

    event.preventDefault();
    const fileInput = document.getElementById('postulacion-cv');
    const file = fileInput && fileInput.files ? fileInput.files[0] : null;

    if (!file) {
        showBolsaAlert('Debes seleccionar un CV antes de enviar la postulacion.', 'danger');
        return;
    }

    const extension = getFileExtension(file.name);
    if (!bolsaCvAllowedExtensions.includes(extension)) {
        showBolsaAlert('El CV debe ser un archivo PDF, DOC o DOCX.', 'danger');
        return;
    }

    if (file.size > bolsaCvMaxBytes) {
        showBolsaAlert('El CV no puede superar ' + bolsaCvMaxLabel + ' en este entorno.', 'danger');
        return;
    }

    const formData = new FormData(event.target);
    formData.append('action', 'postularse');

    try {
        const data = await fetchJson(bolsaControllerUrl, {
            method: 'POST',
            body: formData
        });

        const modalElement = document.getElementById('modalPostulacionBolsa');
        if (modalElement && window.bootstrap && window.bootstrap.Modal) {
            blurActiveElement();
            window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
        }

        event.target.reset();
        showBolsaAlert(data.message, 'success');
        await recargarBolsa();
    } catch (error) {
        showBolsaAlert(error.message, 'danger');
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('modalPostulacionBolsa');
    if (modalElement) {
        modalElement.addEventListener('hide.bs.modal', blurActiveElement);
    }

    recargarBolsa().catch(error => showBolsaAlert(error.message, 'danger'));
});
</script>

<?php include __DIR__ . '/../Template/footer.php'; ?>