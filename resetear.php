<?php
require_once __DIR__ . '/src/config.php';
use App\Model\PasswordReset;
$pageTitle = 'Restablecer Contraseña';
$token = $_GET['token'] ?? '';
$valido = false;
if ($token) {
    $conn = getConnection();
    $reset = PasswordReset::obtenerPorToken($conn, $token);
    if ($reset) {
        $valido = true;
    }
}
include __DIR__ . '/src/Template/head.php';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0"><i class="bi bi-shield-lock"></i> Restablecer Contraseña</h3>
                </div>
                <div class="card-body p-4">
                    <?php if ($valido): ?>
                    <form method="POST" action="procesar_reset.php">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="bi bi-lock"></i> Nueva contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label"><i class="bi bi-lock-fill"></i> Confirmar contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-check"></i> Restablecer</button>
                        </div>
                    </form>
                    <?php else: ?>
                        <div class="alert alert-danger">El enlace es inválido o ha expirado.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/src/Template/footer.php'; ?>
