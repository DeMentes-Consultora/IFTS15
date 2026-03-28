<?php
/**
 * Página de Login - IFTS15 Sistema Web
 * Migrada a PSR-4 con AuthController
 */

// Cargar configuración central con phpdotenv
require_once __DIR__ . '/src/config.php';

use App\Controllers\AuthController;

// Si ya está logueado, redirigir
if (isLoggedIn()) {
    header('Location: ' . BASE_URL);
    exit;
}

$pageTitle = 'Iniciar Sesión';

// Procesar formulario de login usando AuthController
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $authController = new AuthController();
        $authController->login();
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            showError("Error: " . $e->getMessage());
        } else {
            showError('Error interno del sistema');
        }
        header('Location: ' . BASE_URL . '/?error=error_interno');
        exit;
    }
}

include __DIR__ . '/src/Template/head.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </h3>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <?php 
                                switch($_GET['error']) {
                                    case 'credenciales_incorrectas':
                                        echo 'Email o contraseña incorrectos';
                                        break;
                                    case 'campos_vacios':
                                        echo 'Por favor completa todos los campos';
                                        break;
                                    case 'error_interno':
                                        echo 'Error interno del sistema. Intenta nuevamente.';
                                        break;
                                    default:
                                        echo 'Error desconocido';
                                        break;
                                }
                            ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Ingresa tu email" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Contraseña
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Ingresa tu contraseña" 
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <hr>
                    
                    <div class="text-center">
                        <p class="mb-2">
                            <small class="text-muted">¿No tienes una cuenta?</small>
                        </p>
                        <a href="<?php echo BASE_URL; ?>#register" 
                           class="btn btn-outline-success">
                            <i class="bi bi-person-plus"></i> Registrarse
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo BASE_URL; ?>" class="text-decoration-none text-muted">
                            <i class="bi bi-house"></i> Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/Template/footer.php'; ?>