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
$allowedSections = ['ofertas-laborales', 'postulaciones'];
$currentSection = isset($_GET['seccion']) ? (string)$_GET['seccion'] : 'ofertas-laborales';
if (!in_array($currentSection, $allowedSections, true)) {
    $currentSection = 'ofertas-laborales';
}
$pageTitle = $canManage
    ? ($currentSection === 'postulaciones' ? 'Postulaciones' : 'Ofertas laborales')
    : 'Bolsa de Trabajo';

include __DIR__ . '/../Template/head.php';
include __DIR__ . '/../Template/navBar.php';
include __DIR__ . '/../Template/sidebar.php';
?>

<style>
.bolsa-admin-shell {
    --bolsa-border: #e7ebf1;
    --bolsa-head: #f7f9fc;
    --bolsa-text-soft: #6b7280;
    --bolsa-chip-bg: #eef4ff;
    --bolsa-chip-text: #214b9a;
}

.bolsa-admin-shell .card {
    border: 1px solid var(--bolsa-border);
    border-radius: 16px;
    overflow: hidden;
}

.bolsa-admin-shell .card-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.bolsa-stat-card {
    min-width: 180px;
    border-radius: 14px;
    border: 1px solid var(--bolsa-border);
    background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
}

.bolsa-stat-card .card-body {
    padding: .95rem 1rem;
}

.bolsa-stat-label {
    display: block;
    color: var(--bolsa-text-soft);
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: .2rem;
}

.bolsa-stat-value {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1;
    color: #111827;
}

.bolsa-table {
    margin-bottom: 0;
}

.bolsa-table thead th {
    background: var(--bolsa-head);
    color: #49566a;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-bottom: 1px solid var(--bolsa-border);
    padding-top: .9rem;
    padding-bottom: .9rem;
    white-space: nowrap;
}

.bolsa-table tbody td {
    border-color: var(--bolsa-border);
    padding-top: 1rem;
    padding-bottom: 1rem;
    vertical-align: middle;
}

.bolsa-table tbody tr:hover {
    background: #fbfcff;
}

.bolsa-offer-title {
    display: block;
    font-size: .97rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: .2rem;
}

.bolsa-offer-snippet,
.bolsa-meta-text {
    color: var(--bolsa-text-soft);
    font-size: .85rem;
    line-height: 1.35;
}

.bolsa-count-chip {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .38rem .7rem;
    border-radius: 999px;
    background: var(--bolsa-chip-bg);
    color: var(--bolsa-chip-text);
    font-size: .84rem;
    font-weight: 700;
}

.bolsa-count-chip i {
    font-size: .95rem;
}

.bolsa-cv-link {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .84rem;
    font-weight: 600;
    text-decoration: none;
}

.bolsa-avatar {
    width: 42px;
    height: 42px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #e5e7eb;
    background: #f3f4f6;
}

.bolsa-avatar-placeholder {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f3f4f6;
    color: #9ca3af;
    border: 2px solid #e5e7eb;
}

.bolsa-switch-wrap {
    display: inline-flex;
    align-items: center;
    gap: .55rem;
}

.bolsa-switch-label {
    font-size: .8rem;
    font-weight: 600;
    color: var(--bolsa-text-soft);
}

.bolsa-empty-state {
    padding: 2.5rem 1rem;
    text-align: center;
    color: var(--bolsa-text-soft);
}

.bolsa-empty-state i {
    display: block;
    font-size: 1.9rem;
    margin-bottom: .65rem;
    color: #9ca3af;
}

.bolsa-action-group {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: .45rem;
    flex-wrap: nowrap;
}

.bolsa-icon-btn {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    padding: 0;
    border-width: 0;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
}

.bolsa-icon-btn i {
    font-size: .95rem;
}

.bolsa-icon-btn.btn-outline-secondary {
    background: #1d4ed8;
    color: #ffffff;
}

.bolsa-icon-btn.btn-outline-secondary:hover,
.bolsa-icon-btn.btn-outline-secondary:focus {
    background: #1e40af;
    color: #ffffff;
}

.bolsa-icon-btn.btn-outline-primary {
    background: #f59e0b;
    color: #1f2937;
}

.bolsa-icon-btn.btn-outline-primary:hover,
.bolsa-icon-btn.btn-outline-primary:focus {
    background: #d97706;
    color: #ffffff;
}

.bolsa-icon-btn.btn-outline-danger {
    background: #dc2626;
    color: #ffffff;
}

.bolsa-icon-btn.btn-outline-danger:hover,
.bolsa-icon-btn.btn-outline-danger:focus {
    background: #b91c1c;
    color: #ffffff;
}
</style>

<div class="container py-5 mt-4 bolsa-admin-shell">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="text-muted mb-0">
                <?php echo $canManage
                    ? ($currentSection === 'postulaciones'
                        ? 'Consulta las postulaciones recibidas y el resumen general de la bolsa de trabajo.'
                        : 'Publica ofertas laborales y controla si cada publicacion permanece activa o no.')
                    : 'Aqui se muestran las ofertas laborales publicadas para alumnos.'; ?>
            </p>
        </div>
        <?php if ($canManage && $currentSection === 'postulaciones'): ?>
            <div class="d-flex gap-2 flex-wrap" id="bolsa-resumen-cards">
                <div class="card shadow-sm bolsa-stat-card"><div class="card-body"><span class="bolsa-stat-label">Ofertas publicadas</span><strong class="bolsa-stat-value" id="resumen-publicadas">0</strong></div></div>
                <div class="card shadow-sm bolsa-stat-card"><div class="card-body"><span class="bolsa-stat-label">Postulaciones totales</span><strong class="bolsa-stat-value" id="resumen-postulaciones">0</strong></div></div>
            </div>
        <?php endif; ?>
    </div>

    <div id="bolsa-alertas"></div>

    <?php if ($canManage): ?>
        <?php if ($currentSection === 'ofertas-laborales'): ?>
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
                                        <i class="bi bi-send me-1"></i>Publicar oferta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-megaphone me-2"></i>Ofertas laborales</strong>
                            <a class="btn btn-sm btn-outline-light" href="<?php echo BASE_URL; ?>/src/Controllers/viewController.php?view=bolsa-trabajo&amp;seccion=postulaciones">
                                <i class="bi bi-arrow-right-circle me-1"></i>Ir a Postulaciones
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle bolsa-table">
                                    <thead>
                                        <tr>
                                            <th>Oferta laboral</th>
                                            <th>Postulaciones</th>
                                            <th class="text-end">Activa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-ofertas-admin"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-people me-2"></i>Postulaciones</strong>
                    <a class="btn btn-sm btn-outline-light" href="<?php echo BASE_URL; ?>/src/Controllers/viewController.php?view=bolsa-trabajo&amp;seccion=ofertas-laborales">
                        <i class="bi bi-arrow-left-circle me-1"></i>Ir a Ofertas laborales
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle bolsa-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Titulo de la oferta</th>
                                    <th>Apellido del postulante</th>
                                    <th>CV</th>
                                    <th>Mail</th>
                                    <th>Foto de perfil</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-postulaciones-admin"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($isAlumno): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <strong><i class="bi bi-person-workspace me-2"></i>Mis postulaciones</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle bolsa-table">
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

    <?php if (!$canManage): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong><i class="bi bi-megaphone me-2"></i>Ofertas publicadas</strong>
        </div>
        <div class="card-body">
            <div class="row g-3" id="ofertas-publicadas-grid"></div>
        </div>
    </div>
    <?php endif; ?>
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
                        <input type="hidden" name="id_postulacion_bolsa_trabajo" id="postulacion-id-registro">
                        <input type="hidden" id="postulacion-modo" value="crear">
                        <p class="mb-3">Vas a postularte a: <strong id="postulacion-titulo-oferta"></strong></p>
                        <div class="mb-3">
                            <label for="postulacion-cv" class="form-label">CV en PDF, DOC o DOCX</label>
                            <input type="file" class="form-control" id="postulacion-cv" name="cv" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                            <div class="form-text">Tamaño maximo en este entorno: <?php echo htmlspecialchars($serverCvMaxLabel, ENT_QUOTES, 'UTF-8'); ?>.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="postulacion-submit-btn">Enviar postulacion</button>
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
const bolsaSection = '<?php echo addslashes($currentSection); ?>';
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

function truncateText(value, maxLength = 120) {
    const text = String(value || '').trim();
    if (text.length <= maxLength) {
        return text;
    }
    return text.slice(0, maxLength).trimEnd() + '...';
}

function openPostulationModal(config) {
    const modalElement = document.getElementById('modalPostulacionBolsa');
    const inputOfferId = document.getElementById('postulacion-id-oferta');
    const inputPostulationId = document.getElementById('postulacion-id-registro');
    const inputTitle = document.getElementById('postulacion-titulo-oferta');
    const inputCv = document.getElementById('postulacion-cv');
    const inputMode = document.getElementById('postulacion-modo');
    const submitButton = document.getElementById('postulacion-submit-btn');
    const modalTitle = modalElement ? modalElement.querySelector('.modal-title') : null;

    if (inputOfferId) inputOfferId.value = config.offerId || '';
    if (inputPostulationId) inputPostulationId.value = config.postulationId || '';
    if (inputTitle) inputTitle.textContent = config.title || '';
    if (inputCv) inputCv.value = '';
    if (inputMode) inputMode.value = config.mode || 'crear';
    if (submitButton) submitButton.textContent = config.submitLabel || 'Enviar postulacion';
    if (modalTitle) modalTitle.textContent = config.modalTitle || 'Postularme a la oferta';

    if (modalElement && window.bootstrap && window.bootstrap.Modal) {
        blurActiveElement();
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
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
                <div class="bolsa-action-group">
                    ${postulacion.cv_download_url ? `<a class="btn btn-sm btn-outline-secondary bolsa-icon-btn" href="${escapeHtml(postulacion.cv_download_url)}" rel="noopener noreferrer" title="Descargar CV" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Descargar CV"><i class="bi bi-download"></i></a>` : ''}
                    <button class="btn btn-sm btn-outline-primary bolsa-icon-btn btn-actualizar-postulacion" data-id="${postulacion.id_postulacion_bolsa_trabajo}" data-oferta-id="${postulacion.id_bolsa_trabajo}" data-titulo="${escapeHtml(postulacion.titulo_oferta)}" title="Actualizar o volver a subir CV" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Actualizar o volver a subir CV"><i class="bi bi-arrow-repeat"></i></button>
                    <button class="btn btn-sm btn-outline-danger bolsa-icon-btn btn-cancelar-postulacion" data-id="${postulacion.id_postulacion_bolsa_trabajo}" title="Cancelar postulacion" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Cancelar postulacion"><i class="bi bi-x-lg"></i></button>
                </div>
            </td>
        </tr>
    `).join('');
}

function initBolsaTooltips() {
    if (!window.bootstrap || !window.bootstrap.Tooltip) {
        return;
    }

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => {
        const tooltip = window.bootstrap.Tooltip.getInstance(element);
        if (tooltip) {
            tooltip.dispose();
        }
        new window.bootstrap.Tooltip(element);
    });
}

function renderManagementTable(ofertas) {
    const tbody = document.getElementById('tabla-ofertas-admin');
    if (!tbody) return;

    if (!Array.isArray(ofertas) || ofertas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3"><div class="bolsa-empty-state"><i class="bi bi-inbox"></i>No hay ofertas cargadas.</div></td></tr>';
        return;
    }

    tbody.innerHTML = ofertas.map(oferta => `
        <tr>
            <td>
                <span class="bolsa-offer-title">${escapeHtml(oferta.titulo_oferta)}</span>
                <div class="bolsa-offer-snippet">${escapeHtml(truncateText(oferta.texto_oferta || '', 105))}</div>
            </td>
            <td>
                <span class="bolsa-count-chip">
                    <i class="bi bi-people"></i>
                    ${Number(oferta.postulaciones_totales || 0)}
                </span>
            </td>
            <td class="text-end">
                <div class="bolsa-switch-wrap justify-content-end">
                    <span class="bolsa-switch-label">${Number(oferta.habilitado) === 1 ? 'Activa' : 'Inactiva'}</span>
                    <div class="form-check form-switch d-inline-flex justify-content-end m-0">
                        <input class="form-check-input btn-toggle-oferta" type="checkbox" role="switch" data-id="${oferta.id_bolsa_trabajo}" ${Number(oferta.habilitado) === 1 ? 'checked' : ''}>
                    </div>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderAdminPostulations(postulaciones) {
    const tbody = document.getElementById('tabla-postulaciones-admin');
    if (!tbody) return;

    if (!Array.isArray(postulaciones) || postulaciones.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6"><div class="bolsa-empty-state"><i class="bi bi-person-x"></i>No hay postulaciones recibidas.</div></td></tr>';
        return;
    }

    tbody.innerHTML = postulaciones.map((postulacion, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>
                <span class="bolsa-offer-title mb-0">${escapeHtml(postulacion.titulo_oferta || '-')}</span>
                <span class="bolsa-meta-text">Postulado el ${escapeHtml(formatDate(postulacion.fecha_postulacion))}</span>
            </td>
            <td>
                <span class="bolsa-offer-title mb-0">${escapeHtml(postulacion.apellido || '-')}</span>
                <span class="bolsa-meta-text">${escapeHtml(postulacion.nombre || '')}</span>
            </td>
            <td>
                ${postulacion.cv_download_url ? `<a class="btn btn-sm btn-outline-secondary bolsa-cv-link" href="${escapeHtml(postulacion.cv_download_url)}" rel="noopener noreferrer"><i class="bi bi-download"></i>Descargar</a>` : '<span class="bolsa-meta-text">-</span>'}
            </td>
            <td><span class="bolsa-meta-text">${escapeHtml(postulacion.email || '-')}</span></td>
            <td>
                ${postulacion.foto_perfil_url ? `<img src="${escapeHtml(postulacion.foto_perfil_url)}" alt="Foto perfil" class="bolsa-avatar">` : '<span class="bolsa-avatar-placeholder"><i class="bi bi-person"></i></span>'}
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
    if (canManageBolsa) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=listar-publicadas');
    renderPublishedCards(data.ofertas || []);
    initBolsaTooltips();
}

async function cargarPendientes() {
    if (!canManageBolsa) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=listar-pendientes');
    renderManagementTable(data.ofertas || []);
    initBolsaTooltips();
}

async function cargarPostulacionesGestion() {
    if (!canManageBolsa) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=listar-postulaciones-gestion');
    renderAdminPostulations(data.postulaciones || []);
    initBolsaTooltips();
}

async function cargarResumen() {
    if (!canManageBolsa) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=resumen');
    document.getElementById('resumen-publicadas').textContent = data.resumen.publicadas;
    document.getElementById('resumen-postulaciones').textContent = data.resumen.postulaciones;
}

async function cargarMisPostulaciones() {
    if (!isAlumno) return;
    const data = await fetchJson(bolsaControllerUrl + '?action=listar-mis-postulaciones');
    renderMyPostulations(data.postulaciones || []);
    initBolsaTooltips();
}

async function recargarBolsa() {
    await cargarPublicadas();
    if (canManageBolsa) {
        if (bolsaSection === 'postulaciones') {
            await Promise.all([cargarPostulacionesGestion(), cargarResumen()]);
        } else {
            await cargarPendientes();
        }
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
        openPostulationModal({
            mode: 'crear',
            offerId: postularButton.dataset.id,
            title: postularButton.dataset.titulo || '',
            submitLabel: 'Enviar postulacion',
            modalTitle: 'Postularme a la oferta'
        });
        return;
    }

    const actualizarButton = event.target.closest('.btn-actualizar-postulacion[data-id]');
    if (actualizarButton) {
        openPostulationModal({
            mode: 'actualizar',
            offerId: actualizarButton.dataset.ofertaId,
            postulationId: actualizarButton.dataset.id,
            title: actualizarButton.dataset.titulo || '',
            submitLabel: 'Actualizar CV',
            modalTitle: 'Actualizar CV de la postulacion'
        });
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

    const toggleOferta = event.target.closest('.btn-toggle-oferta[data-id]');
    if (toggleOferta) {
        const formData = new FormData();
        formData.append('action', 'gestionar');
        formData.append('id_bolsa_trabajo', toggleOferta.dataset.id);
        formData.append('accion', toggleOferta.checked ? 'activar' : 'desactivar');

        try {
            const data = await fetchJson(bolsaControllerUrl, {
                method: 'POST',
                body: formData
            });
            showBolsaAlert(data.message, 'success');
            await recargarBolsa();
        } catch (error) {
            toggleOferta.checked = !toggleOferta.checked;
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
    const modeInput = document.getElementById('postulacion-modo');
    const mode = modeInput ? modeInput.value : 'crear';
    formData.append('action', mode === 'actualizar' ? 'actualizar-cv-postulacion' : 'postularse');

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
        modalElement.addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('form-postularse-bolsa');
            const inputMode = document.getElementById('postulacion-modo');
            const inputPostulationId = document.getElementById('postulacion-id-registro');
            const submitButton = document.getElementById('postulacion-submit-btn');
            const modalTitle = modalElement.querySelector('.modal-title');

            if (form) form.reset();
            if (inputMode) inputMode.value = 'crear';
            if (inputPostulationId) inputPostulationId.value = '';
            if (submitButton) submitButton.textContent = 'Enviar postulacion';
            if (modalTitle) modalTitle.textContent = 'Postularme a la oferta';
        });
    }

    initBolsaTooltips();

    recargarBolsa().catch(error => showBolsaAlert(error.message, 'danger'));
});
</script>

<?php include __DIR__ . '/../Template/footer.php'; ?>