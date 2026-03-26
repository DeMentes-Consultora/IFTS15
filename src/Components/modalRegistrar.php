<script>
// Calcula la edad automáticamente al cambiar la fecha de nacimiento
function calcularEdad() {
    var fechaInput = document.getElementById('registerFechaNacimiento');
    var edadInput = document.getElementById('registerEdad');
    var edadInfo = document.getElementById('edadInfo');
    if (!fechaInput || !edadInput) return;
    var fecha = fechaInput.value;
    if (!fecha) {
        edadInput.value = '';
        if (edadInfo) edadInfo.style.display = 'none';
        return;
    }
    var hoy = new Date();
    var nacimiento = new Date(fecha);
    var edad = hoy.getFullYear() - nacimiento.getFullYear();
    var m = hoy.getMonth() - nacimiento.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
    edadInput.value = edad;
    if (edadInfo) {
        if (edad >= 16 && edad <= 99) {
            edadInfo.style.display = 'inline';
        } else {
            edadInfo.style.display = 'none';
        }
    }
}
// Inicializar edad si ya hay fecha cargada
document.addEventListener('DOMContentLoaded', function() {
    var fechaInput = document.getElementById('registerFechaNacimiento');
    if (fechaInput) {
        calcularEdad();
        fechaInput.addEventListener('change', calcularEdad);
    }
});
</script>

<?php
/**
 * Modal Registrar - IFTS15
 * Componente de modal de registro
 */

// Obtener datos académicos desde la base de datos
use App\ConectionBD\ConectionDB;
use App\Model\User;
$carreras = $comisiones = $añosCursada = $roles = [];
$modalError = null;
try {
    $conectarDB = new ConectionDB();
    $carreras = $conectarDB->getCarreras();
    $comisiones = $conectarDB->getComisiones();
    $añosCursada = $conectarDB->getAñosCursada();
    // Cargar roles habilitados (se usan para mostrar el select si el usuario actual puede asignar roles)
    try {
        $roles = User::obtenerRolesHabilitados($conectarDB->getConnection());
    } catch (\Throwable $e) {
        // No fatal: el modal puede funcionar aún sin el listado de roles
        error_log('No se pudieron cargar roles en modalRegistrar (User::obtenerRolesHabilitados): ' . $e->getMessage());
    }
} catch (\Throwable $e) {
    $modalError = 'Error al cargar datos académicos: ' . $e->getMessage();
}
?>

<?php if ($modalError): ?>
    <div class="alert alert-danger m-3">⚠️ <?= htmlspecialchars($modalError) ?></div>
<?php endif; ?>
<div class="modal fade" id="modalRegistrar" tabindex="-1" aria-labelledby="modalRegistrarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                        <!-- Mensaje de error general de registro -->
                        <?php if (isset($_SESSION['register_message'])): ?>
                            <div class="invalid-feedback d-block text-center mb-3" style="font-size:1.1em;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                <?= htmlspecialchars($_SESSION['register_message'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalRegistrarLabel">
                    <i class="bi bi-person-plus me-2"></i>
                    Crear Cuenta Nueva
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>



            <form action="<?php echo BASE_URL; ?>/src/Controllers/AuthController.php" method="POST" id="formRegistrar" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="registerFotoPerfil" class="form-label">Foto de Perfil (opcional)</label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="registerFotoPerfil"
                                                   name="foto_perfil"
                                                   accept="image/*">
                                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo recomendado: 2MB.</small>
                                        </div>
                <input type="hidden" name="action" value="register">
                <?php if (isset($modal_included_from)): ?>
                    <input type="hidden" name="modal_included_from" value="<?= htmlspecialchars($modal_included_from) ?>">
                <?php endif; ?>
                <div class="modal-body">
                    <div class="row">
                        <!-- Datos personales -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-person me-1"></i>Datos Personales
                            </h6>
                            
                            <div class="mb-3">
                                <label for="registerNombre" class="form-label">Nombre *</label>
                                <input type="text"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['nombre'])) echo ' is-invalid'; ?>"
                                       id="registerNombre"
                                       name="nombre"
                                       required
                                       placeholder="Tu nombre"
                                       value="<?= htmlspecialchars($_SESSION['register_old']['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?php if(isset($_SESSION['register_errors']['nombre'])): ?>
                                    <?php foreach($_SESSION['register_errors']['nombre'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerApellido" class="form-label">Apellido *</label>
                                <input type="text"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['apellido'])) echo ' is-invalid'; ?>"
                                       id="registerApellido"
                                       name="apellido"
                                       required
                                       placeholder="Tu apellido"
                                       value="<?= htmlspecialchars($_SESSION['register_old']['apellido'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?php if(isset($_SESSION['register_errors']['apellido'])): ?>
                                    <?php foreach($_SESSION['register_errors']['apellido'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerDni" class="form-label">DNI *</label>
                                <input type="text"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['dni'])) echo ' is-invalid'; ?>"
                                       id="registerDni"
                                       name="dni"
                                       required
                                       pattern="\d{7,8}"
                                       placeholder="12345678"
                                       value="<?= htmlspecialchars($_SESSION['register_old']['dni'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?php if(isset($_SESSION['register_errors']['dni'])): ?>
                                    <?php foreach($_SESSION['register_errors']['dni'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerFechaNacimiento" class="form-label">Fecha de Nacimiento *</label>
                                <input type="date"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['fecha_nacimiento'])) echo ' is-invalid'; ?>"
                                       id="registerFechaNacimiento"
                                       name="fecha_nacimiento"
                                       required
                                       onchange="calcularEdad()"
                                       value="<?= htmlspecialchars($_SESSION['register_old']['fecha_nacimiento'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <small class="form-text text-muted">La edad se calculará automáticamente</small>
                                <?php if(isset($_SESSION['register_errors']['fecha_nacimiento'])): ?>
                                    <?php foreach($_SESSION['register_errors']['fecha_nacimiento'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <?php
                            // Mostrar select de roles sólo si el usuario actual tiene id_rol 3 (Administrativo) o 5 (Administrador)
                            $currentUserRole = isset($_SESSION['id_rol']) ? intval($_SESSION['id_rol']) : null;
                            if (in_array($currentUserRole, [3, 5], true)):
                            ?>
                                <div class="mb-3">
                                    <label for="registerRol" class="form-label">Rol *</label>
                                    <select class="form-select" id="registerRol" name="id_rol" required>
                                        <option value="">Seleccionar rol...</option>
                                        <?php if (empty($roles)): ?>
                                            <option value="" disabled style="color:red;">No hay roles habilitados</option>
                                        <?php else: ?>
                                            <?php foreach ($roles as $rol): ?>
                                                <?php
                                                // Mostrar reglas:
                                                // - Si el creador es 3 o 5 muestra todos los roles
                                                // - Si no, ocultar Directivo (4) y Administrador (5)
                                                $rolId = intval($rol['id_rol']);
                                                $creatorRole = isset($_SESSION['id_rol']) ? intval($_SESSION['id_rol']) : null;
                                                if (!in_array($creatorRole, [3, 5], true) && in_array($rolId, [4, 5], true)) {
                                                    continue; // no mostrar estas opciones a usuarios sin permisos
                                                }
                                                ?>
                                                <option value="<?= htmlspecialchars($rol['id_rol']) ?>"><?= htmlspecialchars($rol['rol']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted">Asignar rol al nuevo usuario</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Datos de contacto y cuenta -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-envelope me-1"></i>Datos de Cuenta
                            </h6>
                            
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">Email *</label>
                                <input type="email"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['email'])) echo ' is-invalid'; ?>"
                                       id="registerEmail"
                                       name="email"
                                       required
                                       placeholder="tu.email@ejemplo.com"
                                       value="<?= htmlspecialchars($_SESSION['register_old']['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?php if(isset($_SESSION['register_errors']['email'])): ?>
                                    <?php foreach($_SESSION['register_errors']['email'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerPassword" class="form-label">Contraseña *</label>
                                <input type="password"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['password'])) echo ' is-invalid'; ?>"
                                       id="registerPassword"
                                       name="password"
                                       required
                                       minlength="6"
                                       placeholder="Mínimo 6 caracteres">
                                <?php if(isset($_SESSION['register_errors']['password'])): ?>
                                    <?php foreach($_SESSION['register_errors']['password'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerConfirmPassword" class="form-label">Confirmar Contraseña *</label>
                                <input type="password"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['password'])) echo ' is-invalid'; ?>"
                                       id="registerConfirmPassword"
                                       name="confirm_password"
                                       required
                                       placeholder="Repetir contraseña">
                                <?php if(isset($_SESSION['register_errors']['password'])): ?>
                                    <?php foreach($_SESSION['register_errors']['password'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerTelefono" class="form-label">Teléfono</label>
                                <input type="tel"
                                       class="form-control<?php if(isset($_SESSION['register_errors']['telefono'])) echo ' is-invalid'; ?>"
                                       id="registerTelefono"
                                       name="telefono"
                                       placeholder="11-1234-5678"
                                       value="<?= htmlspecialchars($_SESSION['register_old']['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?php if(isset($_SESSION['register_errors']['telefono'])): ?>
                                    <?php foreach($_SESSION['register_errors']['telefono'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerEdad" class="form-label">Edad (calculada automáticamente)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="registerEdad" 
                                       name="edad" 
                                       required
                                       min="16" 
                                       max="99" 
                                       readonly
                                       style="background-color: #f8f9fa;"
                                       placeholder="Se calculará desde la fecha de nacimiento">
                                <small class="form-text text-success" id="edadInfo" style="display: none;">
                                    <i class="bi bi-check-circle me-1"></i>Edad calculada correctamente
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Datos Académicos -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-3 mt-3">
                                <i class="bi bi-mortarboard me-1"></i>Datos Académicos
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="registerCarrera" class="form-label">Carrera *</label>
                                <select class="form-select<?php if(isset($_SESSION['register_errors']['id_carrera'])) echo ' is-invalid'; ?>" id="registerCarrera" name="id_carrera" required>
                                    <option value="">Seleccionar carrera...</option>
                                    <?php if (empty($carreras)): ?>
                                        <option value="" disabled style="color:red;">No hay carreras habilitadas</option>
                                    <?php else: ?>
                                        <?php foreach ($carreras as $carrera): ?>
                                            <option value="<?= $carrera['id_carrera'] ?>"<?= (isset($_SESSION['register_old']['id_carrera']) && $_SESSION['register_old']['id_carrera'] == $carrera['id_carrera']) ? ' selected' : '' ?>><?= htmlspecialchars($carrera['carrera']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if(isset($_SESSION['register_errors']['id_carrera'])): ?>
                                    <?php foreach($_SESSION['register_errors']['id_carrera'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="registerComision" class="form-label">Comisión *</label>
                                <select class="form-select<?php if(isset($_SESSION['register_errors']['id_comision'])) echo ' is-invalid'; ?>" id="registerComision" name="id_comision" required>
                                    <option value="">Seleccionar comisión...</option>
                                    <?php if (empty($comisiones)): ?>
                                        <option value="" disabled style="color:red;">No hay comisiones habilitadas</option>
                                    <?php else: ?>
                                        <?php foreach ($comisiones as $comision): ?>
                                            <option value="<?= $comision['id_comision'] ?>"<?= (isset($_SESSION['register_old']['id_comision']) && $_SESSION['register_old']['id_comision'] == $comision['id_comision']) ? ' selected' : '' ?>>Comisión <?= htmlspecialchars($comision['comision']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if(isset($_SESSION['register_errors']['id_comision'])): ?>
                                    <?php foreach($_SESSION['register_errors']['id_comision'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="registerAñoCursada" class="form-label">Año a Cursar *</label>
                                <select class="form-select<?php if(isset($_SESSION['register_errors']['id_añoCursada'])) echo ' is-invalid'; ?>" id="registerAñoCursada" name="id_añoCursada" required>
                                    <option value="">Seleccionar año...</option>
                                    <?php if (empty($añosCursada)): ?>
                                        <option value="" disabled style="color:red;">No hay años habilitados</option>
                                    <?php else: ?>
                                        <?php foreach ($añosCursada as $año): ?>
                                            <option value="<?= $año['id_añoCursada'] ?>"<?= (isset($_SESSION['register_old']['id_añoCursada']) && $_SESSION['register_old']['id_añoCursada'] == $año['id_añoCursada']) ? ' selected' : '' ?>><?= $año['año'] ?>° Año</option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if(isset($_SESSION['register_errors']['id_añoCursada'])): ?>
                                    <?php foreach($_SESSION['register_errors']['id_añoCursada'] as $err): ?>
                                        <div class="invalid-feedback d-block"><?= htmlspecialchars($err) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Términos y condiciones -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="aceptarTerminos" 
                                       required>
                                <label class="form-check-label small" for="aceptarTerminos">
                                    Acepto los 
                                    <a href="javascript:void(0)" class="text-decoration-none">términos y condiciones</a> 
                                    y la 
                                    <a href="javascript:void(0)" class="text-decoration-none">política de privacidad</a> 
                                    del IFTS15.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-person-plus me-1"></i>Crear Cuenta
                    </button>
                </div>
            </form>
            <div class="modal-footer border-top-0 pt-0">
                <small class="text-muted w-100 text-center">
                    ¿Ya tienes cuenta? 
                    <a href="#modalLogin" 
                       data-bs-dismiss="modal" 
                       data-bs-toggle="modal" 
                       data-bs-target="#modalLogin"
                       class="text-decoration-none">Inicia sesión aquí</a>
                </small>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['register_errors'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalRegistrar');
    if (modalEl) {
        try {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } catch (e) {
            console.error('No se pudo abrir el modalRegistrar:', e);
        }
    }
});
</script>
<?php
// Limpiar errores y valores viejos después de mostrar el modal
unset($_SESSION['register_message'], $_SESSION['register_errors'], $_SESSION['register_old']);
?>
// Calcula la edad automáticamente al cambiar la fecha de nacimiento
function calcularEdad() {
    var fechaInput = document.getElementById('registerFechaNacimiento');
    var edadInput = document.getElementById('registerEdad');
    var edadInfo = document.getElementById('edadInfo');
    if (!fechaInput || !edadInput) return;
    var fecha = fechaInput.value;
    if (!fecha) {
        edadInput.value = '';
        if (edadInfo) edadInfo.style.display = 'none';
        return;
    }
    var hoy = new Date();
    var nacimiento = new Date(fecha);
    var edad = hoy.getFullYear() - nacimiento.getFullYear();
    var m = hoy.getMonth() - nacimiento.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
    edadInput.value = edad;
    if (edadInfo) {
        if (edad >= 16 && edad <= 99) {
            edadInfo.style.display = 'inline';
        } else {
            edadInfo.style.display = 'none';
        }
    }
}
// Inicializar edad si ya hay fecha cargada
document.addEventListener('DOMContentLoaded', function() {
    var fechaInput = document.getElementById('registerFechaNacimiento');
    if (fechaInput) {
        calcularEdad();
        fechaInput.addEventListener('change', calcularEdad);
    }
});
</script>
<?php endif; ?>