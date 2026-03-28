<?php
// Componente de tabla de usuarios
// Variables esperadas: $usuarios (array), $page, $limit, $total

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF específico para la acción de toggle (si no existe)
if (empty($_SESSION['csrf_usuario_toggle'])) {
    try {
        $_SESSION['csrf_usuario_toggle'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        // fallback
        $_SESSION['csrf_usuario_toggle'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$csrfToken = $_SESSION['csrf_usuario_toggle'];
?>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="card-title">Listado de Usuarios</h5>
            <div>
                <button type="button" class="btn btn-outline-light btn-sm"  style="color:black" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
                    <span class="d-inline d-sm-none"><i class="bi bi-person-plus"></i></span>
                    <span class="d-none d-sm-inline"><i class="bi bi-person-plus me-1"></i> Registrar Usuario</span>
                </button>
                <!--<span class="text-muted">Mostrando página <?= intval($page) ?></span> Muestra en que pagina esta-->
            </div>
        </div>  

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tabla-usuarios">
                <thead class="table-light">
                    <tr>
                        <th>N°</th>
                        <th>Rol</th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Habilitado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php $i = ($page - 1) * $limit + 1;
                        foreach ($usuarios as $u): ?>
                            <tr data-id="<?= (int)$u['id_usuario'] ?>">
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($u['role_name'] ?? 'Usuario') ?></td>
                                <td><?= htmlspecialchars($u['apellido'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['nombre'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['telefono'] ?? '') ?></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-habilitado" type="checkbox" role="switch" id="habilitado-<?= (int)$u['id_usuario'] ?>" data-id="<?= (int)$u['id_usuario'] ?>" <?= (isset($u['habilitado']) && intval($u['habilitado']) === 1) ? 'checked' : '' ?>>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación Bootstrap -->
        <?php
        $totalPages = ($limit > 0) ? ceil($total / $limit) : 1;
        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?');
        $queryParams = $_GET;
        ?>
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Paginación usuarios">
                <ul class="pagination justify-content-center mt-3">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <?php $queryParams['page'] = $p;
                        $queryParams['limit'] = $limit;
                        $href = $currentUrl . '?' . http_build_query($queryParams); ?>
                        <li class="page-item <?= $p == $page ? 'active' : '' ?>"><a class="page-link" href="<?= $href ?>"><?= $p ?></a></li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<!-- Contenedor para toasts dinámicos -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div id="toasts-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let csrfToken = '<?= $csrfToken ?>';
        const toggles = document.querySelectorAll('.toggle-habilitado');
        toggles.forEach(function(cb) {
            cb.addEventListener('change', function() {
                const id = this.dataset.id;
                const habilitado = this.checked ? 1 : 0;

                // prevenir dobles envíos: deshabilitar el checkbox hasta recibir respuesta
                cb.disabled = true;

                fetch('<?= BASE_URL ?>/src/Controllers/usuarioController.php?action=toggle', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + encodeURIComponent(id) + '&habilitado=' + encodeURIComponent(habilitado) + '&csrf_token=' + encodeURIComponent(csrfToken)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Actualizado correctamente', 'success');
                            if (data.new_csrf) {
                                csrfToken = data.new_csrf;
                            }
                        } else {
                            showToast('Error actualizando: ' + (data.error || 'error desconocido'), 'danger');
                            // revertir checkbox
                            cb.checked = !cb.checked;
                        }
                    })
                    .catch(err => {
                        console.error('Error AJAX toggle:', err);
                        showToast('Error comunicándose con el servidor', 'danger');
                        cb.checked = !cb.checked;
                    })
                    .finally(() => {
                        // reactivar el checkbox sin importar el resultado
                        cb.disabled = false;
                    });
            });
        });

        // Toast helper using Bootstrap 5
        function showToast(message, type = 'info', timeout = 4000) {
            const container = document.getElementById('toasts-container');
            if (!container) return;

            const toastId = 'toast-' + Date.now();
            const toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center text-bg-' + (type === 'danger' ? 'danger' : (type === 'success' ? 'success' : 'secondary')) + ' border-0';
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
            const bsToast = new bootstrap.Toast(toastEl, {
                delay: timeout
            });
            bsToast.show();

            // eliminar del DOM cuando se oculta
            toastEl.addEventListener('hidden.bs.toast', function() {
                toastEl.remove();
            });
        }

    });
</script>