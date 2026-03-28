</div>
<?php if (isset($_SESSION['login_message'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('modalLogin'));
    modal.show();
});
</script>
<?php endif; ?>
<?php
/**
 * Modal Login - IFTS15
 * Componente de modal de inicio de sesión
 */
?>

<!-- Modal Login -->
<div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-dark">
                <h5 class="modal-title" id="modalLoginLabel">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Iniciar Sesión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Área de alertas -->
            <?php if (isset($_SESSION['login_message'])): ?>
                <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                    <strong><?= htmlspecialchars($_SESSION['login_message'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['login_message']); endif; ?>
            <form action="<?php echo BASE_URL; ?>/src/Controllers/AuthController.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="loginEmail" 
                               name="email" 
                               required 
                               placeholder="tu.email@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="loginPassword" 
                               name="password" 
                               required 
                               placeholder="Tu contraseña">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="recordarme">
                        <label class="form-check-label" for="recordarme">
                            Recordarme
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Ingresar
                    </button>
                </div>
            </form>
            <div class="modal-footer border-top-0 pt-0">
                <small class="text-muted w-100 text-center">
                    ¿No tienes cuenta? 
                    <a href="#modalRegistrar" 
                       data-bs-dismiss="modal" 
                       data-bs-toggle="modal" 
                       data-bs-target="#modalRegistrar"
                       class="text-decoration-none">Regístrate aquí</a>
                </small>
            </div>
        </div>
    </div>
</div>