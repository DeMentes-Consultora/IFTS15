<?php
// Página para solicitar recuperación de contraseña
require_once __DIR__ . '/src/config.php';
use App\Controllers\AuthController;
$pageTitle = 'Recuperar Contraseña';
include __DIR__ . '/src/Template/head.php';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark text-center">
                    <h3 class="mb-0"><i class="bi bi-key"></i> Recuperar Contraseña</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="enviar_recupero.php">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu email" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg"><i class="bi bi-send"></i> Enviar enlace de recuperación</button>
                        </div>
                    </form>
                    <hr>
                    <div class="text-center mt-3">
                        <a href="login.php" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Volver al login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/src/Template/footer.php'; ?>
