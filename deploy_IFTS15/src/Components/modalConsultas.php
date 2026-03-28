<?php
/**
 * Modal de Consultas - IFTS15
 * Archivo: src/Components/modalConsultas.php
 */

// Cargar carreras desde la base de datos
use App\ConectionBD\ConectionDB;
$carreras = [];
$modalError = null;
try {
    $conectarDB = new ConectionDB();
    $carreras = $conectarDB->getCarreras();
} catch (\Throwable $e) {
    $modalError = 'Error al cargar carreras: ' . $e->getMessage();
}
?>

<!-- Modal de Consultas -->
<div class="modal fade" id="consultasModal" tabindex="-1" aria-labelledby="consultasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consultasModalLabel">
                    <i class="fa fa-question-circle"></i> 
                    Consultas y Contacto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Formulario de consultas -->
                    <div class="col-lg-8">
                        <div class="consultas-form-container">
                            <h6 class="mb-3 text-primary">
                                <i class="fa fa-envelope"></i>
                                Envíanos tu consulta
                            </h6>
                            
                            <!-- Área de alertas -->
                            <?php if (isset($_SESSION['consultas_message'])): ?>
                                <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                                    <strong><?= htmlspecialchars($_SESSION['consultas_message'], ENT_QUOTES, 'UTF-8') ?></strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php unset($_SESSION['consultas_message']); endif; ?>
                            
                            <form method="post" action="<?php echo (defined('BASE_URL') ? BASE_URL : '') . '/src/Controllers/consultasController.php'; ?>">
                                <input type="hidden" name="action" value="enviar_consulta_modal">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="modalNombre" class="form-label">
                                            <i class="fa fa-user"></i> Nombre completo *
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="modalNombre" 
                                               name="nombre" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="modalEmail" class="form-label">
                                            <i class="fa fa-envelope"></i> Email *
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="modalEmail" 
                                               name="email" 
                                               value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                               <?php echo (isset($_SESSION['email']) ? 'readonly' : 'required'); ?>>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="modalTelefono" class="form-label">
                                            <i class="fa fa-phone"></i> Teléfono
                                        </label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="modalTelefono" 
                                               name="telefono">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="modalCarrera" class="form-label">
                                            <i class="fa fa-graduation-cap"></i> Carrera de interés
                                        </label>
                                        <select class="form-control" id="modalCarrera" name="carrera">
                                            <option value="">Seleccionar carrera...</option>
                                            <?php if (empty($carreras)): ?>
                                                <option value="" disabled style="color:red;">No hay carreras habilitadas</option>
                                            <?php else: ?>
                                                <?php foreach ($carreras as $carrera): ?>
                                                    <option value="<?= $carrera['id_carrera'] ?>"><?= htmlspecialchars($carrera['carrera']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="modalConsulta" class="form-label">
                                        <i class="fa fa-comment"></i> Tu consulta *
                                    </label>
                                    <textarea class="form-control" 
                                              id="modalConsulta" 
                                              name="mensaje" 
                                              rows="4" 
                                              placeholder="Escribe aquí tu consulta..." 
                                              required></textarea>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg" id="btnEnviarConsulta">
                                        <i class="fa fa-paper-plane"></i>
                                        Enviar Consulta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Información de contacto -->
                    <div class="col-lg-4">
                        <div class="consultas-info-container">
                            <div class="info-section mb-4">
                                <h6 class="text-primary">
                                    <i class="fa fa-info-circle"></i>
                                    Información de Contacto
                                </h6>
                                
                                <div class="contact-item mb-3">
                                    <div class="contact-icon">
                                        <i class="fa fa-map-marker-alt text-danger"></i>
                                    </div>
                                    <div class="contact-content">
                                        <strong>Dirección</strong>
                                        <p class="small text-muted">
                                            Av. Corrientes 1234<br>
                                            Ciudad Autónoma de Buenos Aires<br>
                                            CP: C1043AAZ
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="contact-item mb-3">
                                    <div class="contact-icon">
                                        <i class="fa fa-phone text-success"></i>
                                    </div>
                                    <div class="contact-content">
                                        <strong>Teléfonos</strong>
                                        <p class="small text-muted">
                                            <strong>Secretaría:</strong> (011) 4123-4567<br>
                                            <strong>Admisiones:</strong> (011) 4123-4568<br>
                                            <strong>WhatsApp:</strong> (011) 15-1234-5678
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="contact-item mb-3">
                                    <div class="contact-icon">
                                        <i class="fa fa-envelope text-info"></i>
                                    </div>
                                    <div class="contact-content">
                                        <strong>Emails</strong>
                                        <p class="small text-muted">
                                            <strong>General:</strong> info@ifts15.edu.ar<br>
                                            <strong>Admisiones:</strong> admisiones@ifts15.edu.ar<br>
                                            <strong>Académico:</strong> academico@ifts15.edu.ar
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="contact-item mb-4">
                                    <div class="contact-icon">
                                        <i class="fa fa-clock text-warning"></i>
                                    </div>
                                    <div class="contact-content">
                                        <strong>Horarios de Atención</strong>
                                        <p class="small text-muted">
                                            <strong>Lunes a Viernes:</strong> 9:00 a 18:00 hs<br>
                                            <strong>Sábados:</strong> 9:00 a 13:00 hs<br>
                                            <strong>Domingos:</strong> Cerrado
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Redes sociales -->
                            <div class="info-section mb-4">
                                <h6 class="text-primary">
                                    <i class="fa fa-share-alt"></i>
                                    Síguenos
                                </h6>
                                <div class="social-buttons">
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-facebook"></i> Facebook
                                    </a>
                                    <a href="#" class="btn btn-outline-info btn-sm">
                                        <i class="fa fa-twitter"></i> Twitter
                                    </a>
                                    <a href="#" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-instagram"></i> Instagram
                                    </a>
                                    <a href="#" class="btn btn-outline-dark btn-sm">
                                        <i class="fa fa-youtube"></i> YouTube
                                    </a>
                                </div>
                            </div>
                            
                            <!-- FAQ -->
                            <div class="info-section">
                                <h6 class="text-primary">
                                    <i class="fa fa-question"></i>
                                    Preguntas Frecuentes
                                </h6>
                                <p class="small text-muted">
                                    ¿Tienes preguntas frecuentes? Consulta nuestra sección de 
                                    preguntas frecuentes donde respondemos las dudas más comunes.
                                </p>
                                <a href="<?php echo (defined('BASE_URL') ? BASE_URL : '') . '/pages/faq.php'; ?>" class="btn btn-outline-success btn-sm">
                                    <i class="fa fa-book"></i> Ver FAQ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cerrar
                </button>
                <a href="<?php echo (defined('BASE_URL') ? BASE_URL : '') . '/pages/consultas.php'; ?>" class="btn btn-outline-primary">
                    <i class="fa fa-external-link-alt"></i> Ver página completa
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['consultas_message'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('consultasModal'));
    modal.show();
});
</script>
<?php endif; ?>