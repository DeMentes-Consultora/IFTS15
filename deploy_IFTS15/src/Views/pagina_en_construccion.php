<?php
/**
 * Página en construcción - muestra imagen grande
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config.php';
?>
<?php include_once __DIR__ . '/../Template/head.php'; ?>

<div class="container py-5 text-center">
    <h1 class="mb-4">Página en construcción</h1>
    <p class="text-muted">Disculpe las molestias — esta sección está en desarrollo.</p>
    <div class="my-4">
        <img src="<?php echo BASE_URL; ?>/src/Public/images/pagina en construccion.png" alt="Página en construcción" class="img-fluid" style="max-width:100%; height:auto;">
    </div>
    <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-secondary">Volver al inicio</a>
</div>

<?php include_once __DIR__ . '/../Template/footer.php'; ?>
