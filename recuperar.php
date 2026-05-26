<?php
// Página para solicitar recuperación de contraseña
require_once __DIR__ . '/src/config.php';
use App\Controllers\AuthController;

$error = $_GET['error'] ?? '';
$enviado = isset($_GET['enviado']) && $_GET['enviado'] === '1';
$detail = trim((string) ($_GET['detail'] ?? ''));

$errorMessages = [
    'campos_vacios' => 'Ingresá un correo electrónico para continuar.',
    'email_no_encontrado' => 'No existe un usuario registrado con ese correo.',
    'token_fallido' => 'No se pudo generar o guardar el token de recupero. Revisá la tabla password_resets en producción.',
    'smtp_fallido' => 'El servidor no pudo enviar el correo por SMTP. Revisá la configuración o ejecutá smtp_test.php en producción.',
    'envio_fallido' => 'No se pudo enviar el correo de recupero. Revisá spam o intentá nuevamente.',
];

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
                    <?php if ($enviado): ?>
                        <div class="alert alert-success" role="alert">
                            Te enviamos un enlace de recuperación. Revisá también la carpeta de spam o promociones.
                        </div>
                    <?php elseif (isset($errorMessages[$error])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($errorMessages[$error], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <?php if ($detail !== ''): ?>
                            <div class="alert alert-secondary" role="alert">
                                <strong>Detalle técnico:</strong>
                                <?= htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form method="POST" action="enviar_recupero.php">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu email" value="<?= htmlspecialchars($_GET['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
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
