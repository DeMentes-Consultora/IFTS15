<?php

/**
 * Página Principal - IFTS15 (Versión Corregida)
 * Basada en el patrón MVC con phpdotenv y PSR-4
 */

// Cargar configuración central (incluye autoloader)
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Model/SiteCustomizationModel.php';

// Usar las clases con namespaces
use App\ConectionBD\ConectionDB;
use App\Model\Person;
use App\Model\User;
use App\Controllers\AuthController;
use App\Controllers\EstadisticasController;
use App\Model\SiteCustomizationModel;

// Instanciar conexión a la base de datos
try {
    $conectarDB = new ConectionDB();
    $conn = $conectarDB->getConnection();
    $customization = SiteCustomizationModel::getPublicConfig($conn);
    $siteCarousel = is_array($customization['carousel'] ?? null) ? $customization['carousel'] : [];
} catch (Exception $e) {
    error_log("Error cargando MVC: " . $e->getMessage());
    // En caso de error, continuar sin MVC para mostrar la página básica
    $conn = null;
    $siteCarousel = [];
}

// Verificar estado de sesión
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userEmail = $_SESSION['email'] ?? '';
// Preferir id_rol numérico cuando esté presente en la sesión
$userIdRol = isset($_SESSION['id_rol']) ? intval($_SESSION['id_rol']) : (isset($_SESSION['role_id']) ? intval($_SESSION['role_id']) : null);
$roleNames = [1 => 'Alumno', 2 => 'Profesor', 3 => 'Administrativo', 4 => 'Directivo', 5 => 'Administrador'];
$userRole = $roleNames[$userIdRol] ?? 'Alumno';
?>

<?php include __DIR__ . '/src/Template/head.php'; ?>

<!-- ALERTA GLOBAL PARA REGISTRO (si existe) -->
<?php if (isset($_SESSION['register_message'])): ?>
    <div id="alertaRegistroFlotante" class="alert alert-info alert-dismissible fade show" role="alert" style="position:fixed;top:30px;left:50%;transform:translateX(-50%);z-index:2000;min-width:350px;max-width:90vw;">
        <strong><?= htmlspecialchars($_SESSION['register_message'], ENT_QUOTES, 'UTF-8') ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var modalEl = document.getElementById('modalRegistrar');
            if (modalEl) {
                try {
                    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                } catch (e) {
                    console.error('No se pudo abrir el modalRegistrar:', e);
                }
            }
        }, 200); // Pequeño retardo para asegurar visibilidad
    });
    </script>
<?php endif; ?>

<?php include __DIR__ . '/src/Template/navBar.php'; ?>

<!-- Sidebar Offcanvas -->
<?php if ($isLoggedIn): ?>
    <?php include __DIR__ . '/src/Template/sidebar.php'; ?>
<?php else: ?>
    <!-- Sidebar para usuarios no logueados / aca tambien se puede cambiar desde donde sale el sidebar "offcanvas-end/star/up/down"-->
    <div class="offcanvas offcanvas-end text-bg-dark"
        tabindex="-1"
        id="sidebarOffcanvasGuest"
        aria-labelledby="sidebarOffcanvasLabel">

        <!-- Header del offcanvas -->
        <div class="offcanvas-header bg-secondary text-white">
            <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">
                <i class="bi bi-house-door me-2"></i>
                Menú Principal
            </h5>
            <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <!-- Información para usuarios no logueados -->
        <div class="p-3 border-bottom border-secondary">
            <div class="text-center">
                <i class="bi bi-person-plus fs-2 text-warning mb-2"></i>
                <p class="mb-1 text-light">
                    <strong>Bienvenido</strong>
                </p>
                <p class="mb-0 text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    Usuario Invitado
                </p>
            </div>
        </div>

        <!-- Menú de navegación -->
        <div class="offcanvas-body p-0">
            <nav class="nav nav-pills flex-column gap-2 p-3">

                <!-- Sección: Navegación Principal -->
                <div class="sidebar-heading text-muted mb-2">
                    <i class="bi bi-compass me-1"></i>
                    NAVEGACIÓN
                </div>

                <a class="nav-link text-light d-flex align-items-center gap-2"
                    href="<?php echo BASE_URL; ?>/index.php">
                    <i class="bi bi-house-door"></i>
                    <span>Inicio</span>
                </a>

                <a class="nav-link text-light d-flex align-items-center gap-2"
                    href="<?php echo BASE_URL; ?>/src/Controllers/viewController.php?view=realizador-productor-tv">
                    <i class="bi bi-camera-video"></i>
                    <span>Información de Carrera</span>
                </a>

                <!-- Separador -->
                <hr class="border-secondary my-3">

                <!-- Sección: Acceso al Sistema (eliminada, ahora en navbar) -->

                <!-- Separador -->
                <hr class="border-secondary my-3">

                <!-- Sección: Ayuda y Soporte -->
                <div class="sidebar-heading text-muted mb-2">
                    <i class="bi bi-question-circle me-1"></i>
                    AYUDA Y SOPORTE
                </div>

                <a class="nav-link text-light d-flex align-items-center gap-2"
                    href="#consultasModal"
                    data-bs-toggle="modal"
                    data-bs-target="#consultasModal">
                    <i class="bi bi-chat-dots text-info"></i>
                    <span>Consultas</span>
                </a>

                <a class="nav-link text-light d-flex align-items-center gap-2"
                    href="javascript:void(0)">
                    <i class="bi bi-info-circle text-primary"></i>
                    <span>Acerca de IFTS15</span>
                </a>

                <a class="nav-link text-light d-flex align-items-center gap-2"
                    href="javascript:void(0)">
                    <i class="bi bi-telephone text-success"></i>
                    <span>Contacto</span>
                </a>

            </nav>
        </div>
    </div>
<?php endif; ?>

<!-- Contenido Principal -->
<main class="flex-fill">

    <!-- Carrusel Hero Section -->
    <?php
    if (empty($siteCarousel)) {
        $siteCarousel = [
            [
                'titulo' => 'Contenidos y Modelos Digitales',
                'descripcion' => 'Creación de contenido audiovisual para medios digitales y televisión',
                'link_url' => null,
                'image_url' => BASE_URL . '/src/Public/images/carrussel_1.jpeg',
            ],
            [
                'titulo' => 'Instituto de Formación Técnica Superior N° 15',
                'descripcion' => 'Carrera: Realizador y Productor Televisivo',
                'link_url' => null,
                'image_url' => BASE_URL . '/src/Public/images/carrussel_2.jpeg',
            ],
            [
                'titulo' => 'Taller de Prácticas Pre Profesionales',
                'descripcion' => 'Experiencia práctica en entornos profesionales reales',
                'link_url' => null,
                'image_url' => BASE_URL . '/src/Public/images/carrussel_3.jpeg',
            ],
        ];
    }
    ?>
    <div id="carouselHero" class="carousel slide carousel-hero" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <?php foreach ($siteCarousel as $idx => $slide): ?>
                <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="<?php echo $idx; ?>" class="<?php echo $idx === 0 ? 'active' : ''; ?>" <?php echo $idx === 0 ? 'aria-current="true"' : ''; ?> aria-label="Slide <?php echo $idx + 1; ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach ($siteCarousel as $idx => $slide): ?>
                <?php
                $slideImage = !empty($slide['image_url']) ? (string)$slide['image_url'] : (BASE_URL . '/src/Public/images/carrussel_' . ($idx + 1) . '.jpeg');
                $slideTitle = trim((string)($slide['titulo'] ?? 'Slide ' . ($idx + 1)));
                $slideDescription = trim((string)($slide['descripcion'] ?? ''));
                ?>
                <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($slideImage, ENT_QUOTES, 'UTF-8'); ?>"
                        class="d-block w-100"
                        alt="<?php echo htmlspecialchars($slideTitle, ENT_QUOTES, 'UTF-8'); ?>"
                        onerror="this.src='https://via.placeholder.com/1200x400/333333/ffd700?text=IMAGEN+NO+ENCONTRADA'">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="carousel-caption-custom">
                            <h2 class="fw-bold text-white"><?php echo htmlspecialchars($slideTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
                            <?php if ($slideDescription !== ''): ?>
                                <p class="lead text-light"><?php echo htmlspecialchars($slideDescription, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Controles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselHero" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselHero" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <div class="container-fluid py-4">
                        <?php if (isset($_SESSION['register_message'])): ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var modal = new bootstrap.Modal(document.getElementById('modalRegistrar'));
                                    modal.show();
                                });
                            </script>
                        <?php endif; ?>
                <?php
                // Mostrar mensaje de registro (error o éxito) si existe
                if (isset($_SESSION['register_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['register_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['register_message']); ?>
                <?php endif; ?>
        <!-- Contenido de la página principal -->

        <?php
        // Mostrar mensajes de error o éxito
        if (isset($_GET['error'])):
            $error_messages = [
                'campos_vacios' => 'Por favor complete todos los campos obligatorios.',
                'email_invalido' => 'El formato del email no es válido.',
                'passwords_no_match' => 'Las contraseñas no coinciden.',
                'password_weak' => 'La contraseña debe tener al menos 8 caracteres.',
                'email_exists' => 'Ya existe una cuenta con este email.',
                'dni_exists' => 'Ya existe una persona registrada con este DNI.',
                'login_failed' => 'Email o contraseña incorrectos.',
                'db_error' => 'Error en la base de datos. Intente nuevamente.',
                'metodo_invalido' => 'Método de solicitud no válido.',
                'accion_invalida' => 'Acción no válida.'
            ];

            $error_message = $error_messages[$_GET['error']] ?? 'Error desconocido.';
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php
        // Mostrar mensajes de éxito
        if (isset($_GET['login']) && $_GET['login'] === 'success'):
        ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>¡Bienvenido!</strong> Has iniciado sesión correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>¡Registro exitoso!</strong> Su cuenta ha sido creada. Ya puede iniciar sesión.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Sesión cerrada:</strong> Ha cerrado sesión correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?php
                $success_messages = [
                    'registro_exitoso' => '¡Registro exitoso! Ya puedes iniciar sesión.',
                    'login_exitoso' => '¡Bienvenido! Has iniciado sesión correctamente.',
                    'logout_exitoso' => 'Sesión cerrada correctamente.'
                ];

                $success_message = $success_messages[$_GET['success']] ?? '¡Operación exitosa!';
                echo htmlspecialchars($success_message);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Card de Bienvenida -->
        <div class="row justify-content-center align-items-stretch mb-4">
            <div class="col-12 col-md-6 d-flex">
                <div class="card card-welcome fade-in flex-fill">
                    <h3 class="text-warning mb-3">
                        <i class="bi bi-award me-2"></i>
                        Realizador y Productor de TV
                    </h3>
                    <p class="lead mb-4">
                        Formamos profesionales creativos y técnicos en el campo audiovisual.
                        Desarrolla tu talento en producción televisiva, dirección y post-producción.
                    </p>
                    <?php if (!$isLoggedIn): ?>
                        <div class="alert alert-info slide-in-left">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>¿Eres estudiante?</strong>
                            Inicia sesión para acceder a tu panel académico, calificaciones y materiales de estudio.
                        </div>
                    <?php endif; ?>
                    <!-- Estadísticas -->
                    <?php
                    $alumnos = $profesores = $carreras = 0;
                    $conexionValida = false;
                    if ($conn) {
                        try {
                            // Verificar conexión intentando una query simple
                            $testQuery = $conn->query("SELECT 1");
                            if ($testQuery) {
                                $conexionValida = true;
                                $alumnos = EstadisticasController::getCantidadAlumnos($conn);
                                $profesores = EstadisticasController::getCantidadProfesores($conn);
                                $carreras = EstadisticasController::getCantidadCarreras($conn);
                            }
                        } catch (Throwable $e) {
                            error_log('Error al verificar conexión MySQLi: ' . $e->getMessage());
                            $conexionValida = false;
                        }
                    }
                    if (!$conexionValida) {
                        echo '<div class="alert alert-danger">No se pudo conectar a la base de datos para mostrar estadísticas.</div>';
                    }
                    ?>
                    <div class="row text-center mt-4">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body py-3">
                                    <i class="bi bi-people-fill fs-2 mb-2"></i>
                                    <h5 class="mb-0"><?php echo $alumnos; ?></h5>
                                    <small>Alumnos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body py-3">
                                    <i class="bi bi-mortarboard-fill fs-2 mb-2"></i>
                                    <h5 class="mb-0"><?php echo $profesores; ?></h5>
                                    <small>Profesores</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body py-3">
                                    <i class="bi bi-camera-video-fill fs-2 mb-2"></i>
                                    <h5 class="mb-0"><?php echo $carreras; ?></h5>
                                    <small>Carreras</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body py-3">
                                    <i class="bi bi-award-fill fs-2 mb-2"></i>
                                    <h5 class="mb-0">180+</h5>
                                    <small>Graduados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Botón Ver Información Completa -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="<?php echo BASE_URL; ?>/src/Controllers/viewController.php?view=realizador-productor-tv"
                                class="btn btn-primary btn-lg w-100 d-block">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Ver Información Completa de la Carrera
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 d-flex">
                <div class="card h-100 flex-fill mb-4" style="min-height:340px;">
                    <div class="card-header bg-info text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-megaphone me-2"></i>
                                Novedades
                            </h6>
                            <?php
                            // Solo el usuario Administrador y Administrativo puede ver el botón
                            $id_rol = $_SESSION['id_rol'] ?? '';
                            if ($isLoggedIn && isAdminRole()) {
                                // Incluir solo el botón, no el modal completo
                                echo '<div class="d-flex justify-content-end mb-0">';
                                echo '<button type="button" class="btn btn-dark btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalNovedad">';
                                echo '<i class="bi bi-plus-circle me-1"></i> Agregar Novedad';
                                echo '</button></div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between" style="height:100%;">
                        <?php
                        // Mostrar la tabla de novedades siempre
                        require_once __DIR__ . '/src/Controllers/novedadesController.php';
                        echo '<div class="table-responsive" style="min-height:220px;max-height:320px;overflow-y:auto;">';
                        echo '<table class="table table-borderless align-middle mb-0 w-100">';
                        echo '<thead><tr><th style="width: 160px;">Fecha</th><th>Novedad</th></tr></thead><tbody>';
                        if (isset($novedades) && count($novedades) > 0) {
                            foreach ($novedades as $nov) {
                                echo '<tr>';
                                echo '<td class="text-muted small">' . date('d/m/Y H:i', strtotime($nov['idCreate'])) . '</td>';
                                echo '<td>' . htmlspecialchars($nov['novedad']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="2" class="text-center text-muted">No hay novedades aún.</td></tr>';
                        }
                        echo '</tbody></table></div>';
                        ?>
                    </div>
                </div>
            </div>
        </div>

    
        <?php if (!$isLoggedIn): ?>
            <!-- Sección de Información Institucional -->
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <div class="row g-4">
                        <div class="col-12 col-md-4">
                            <div class="card h-100 card-info">
                                <div class="card-body text-center">
                                    <i class="bi bi-camera-reels text-primary fs-1 mb-3"></i>
                                    <h5>Producción Audiovisual</h5>
                                    <p class="small text-muted">
                                        Aprende las técnicas más avanzadas en producción
                                        de contenido audiovisual para televisión y medios digitales.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card h-100 card-info">
                                <div class="card-body text-center">
                                    <i class="bi bi-broadcast text-success fs-1 mb-3"></i>
                                    <h5>Dirección Televisiva</h5>
                                    <p class="small text-muted">
                                        Desarrolla habilidades de dirección para programas
                                        de televisión, documentales y contenido streaming.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card h-100 card-info">
                                <div class="card-body text-center">
                                    <i class="bi bi-film text-warning fs-1 mb-3"></i>
                                    <h5>Post-Producción</h5>
                                    <p class="small text-muted">
                                        Domina las herramientas de edición, efectos visuales
                                        y sonorización profesional.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>


<?php
if ($isLoggedIn && isAdminRole()) {
    include __DIR__ . '/src/Components/modalNovedades.php';
}
?>
<!-- Incluir footer del sistema -->
<?php include __DIR__ . '/src/Template/footer.php'; ?>

<!-- Bootstrap JS Bundle se incluye solo en el footer para evitar duplicados -->

<!-- JavaScript personalizado -->
<script>
    // Auto-ocultar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // TEST: Mostrar datos del usuario logueado en consola
        const usuarioLogueado = {
            email: <?php echo json_encode($userEmail); ?>,
            id_rol: <?php echo json_encode($_SESSION['id_rol'] ?? ''); ?>,
            role: <?php echo json_encode($userRole); ?>,
            isLoggedIn: <?php echo json_encode($isLoggedIn); ?>
        };
        console.log('Usuario logueado:', usuarioLogueado);

        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Funcionalidad del footer
        /* const currentYear = new Date().getFullYear();
         document.querySelector('footer p').innerHTML = 
             '© ' + currentYear + ' IFTS N° 15. Todos los derechos reservados.';*/

        // Smooth scroll para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                // Solo si el href no es solo "#"
                if (href && href !== '#') {
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    });
</script>
</body>

</html>