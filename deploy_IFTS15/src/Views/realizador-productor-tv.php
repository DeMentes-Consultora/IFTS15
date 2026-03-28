<?php

/**
 * Realizador y Productor Televisivo - IFTS15
 * Vista específica de la carrera
 */

// Configuración del sistema
require_once __DIR__ . '/../config.php';

// Datos específicos de la página
$pageTitle = 'Realizador y Productor Televisivo - IFTS15';

// Variables para el sistema de templates (necesarias para navbar y sidebar)
// Estado de sesión y rol numérico
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userEmail = $_SESSION['email'] ?? '';
$userIdRol = isset($_SESSION['id_rol']) ? intval($_SESSION['id_rol']) : (isset($_SESSION['role_id']) ? intval($_SESSION['role_id']) : null);
$roleNames = [1 => 'Alumno', 2 => 'Profesor', 3 => 'Administrativo', 4 => 'Directivo', 5 => 'Administrador'];
$userRole = $roleNames[$userIdRol] ?? 'Alumno';

// Compatibilidad con sidebar (que espera $_SESSION['usuario'])
if ($isLoggedIn && !empty($userEmail)) {
    $_SESSION['usuario'] = $userEmail;
}

?>

<?php include __DIR__ . '/../Template/head.php'; ?>

<?php include __DIR__ . '/../Template/navBar.php'; ?>

<!-- Sidebar Offcanvas -->
<?php if ($isLoggedIn): ?>
    <?php include __DIR__ . '/../Template/sidebar.php'; ?>
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

                <!-- Sección: Acceso al Sistema eliminada, ahora en navbar -->

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

<!-- CSS específico para esta página -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/informacionCarreraCss.css">

<!-- Contenido principal con margen para navbar fixed -->

<div class="container mt-4" style="margin-top: 100px !important;">
    <div class="row">
        <div class="col-12">
            <!-- Header específico IFTS15 -->
            <div class="card border-0 shadow-lg mb-4 career-header">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 mb-3">
                                <i class="fa fa-video me-3"></i>
                                Tecnicatura Superior en Realizador y Productor Televisivo
                            </h1>
                            <p class="lead mb-0">
                                <?php echo htmlspecialchars($_ENV['CARD_DESCRIPTION'] ?? ''); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-white rounded p-3 text-dark">
                                <h3 class="text-danger mb-1">3 años</h3>
                                <small class="text-muted">Duración</small>
                                <hr class="my-2">
                                <h5 class="text-primary mb-0">IFTS15</h5>
                                <small class="text-muted">Especialidad</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Características únicas -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 text-center border-danger">
                        <div class="card-body">
                            <img src="<?= BASE_URL ?>/src/Public/images/info_carrera_1.jpeg" alt="Realizador y Productor Televisivo" class="img-fluid mb-3" style="max-height:160px; object-fit:cover;">
                            <p class="text-muted">Desde la conceptualización hasta la emisión</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 text-center border-warning">
                        <div class="card-body">
                            <img src="<?= BASE_URL ?>/src/Public/images/info_carrera_2.jpeg" alt="Realizador y Productor Televisivo" class="img-fluid mb-3" style="max-height:160px; object-fit:cover;">
                            <p class="text-muted">Dirección creativa y técnica de contenidos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 text-center border-success">
                        <div class="card-body">
                            <?php // Reemplazamos el icono por una imagen representativa de la carrera 
                            ?>
                            <img src="<?= BASE_URL ?>/src/Public/images/info_carrera_3.jpeg" alt="Realizador y Productor Televisivo" class="img-fluid mb-3" style="max-height:160px; object-fit:cover;">
                            <p class="text-muted">Tecnologías y plataformas digitales modernas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del IFTS15 -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card info-card h-100">
                        <div class="card-header card-header-gray">
                            <h4 class="mb-0">
                                <i class="fa fa-info-circle"></i>
                                Información de la Carrera
                            </h4>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Instituto:</dt>
                                <dd class="col-sm-8">Instituto de Formación Técnica Superior Nº 15</dd>

                                <dt class="col-sm-4">Área de formación:</dt>
                                <dd class="col-sm-8">Comunicación y Producción Audiovisual</dd>

                                <dt class="col-sm-4">Título Otorgado:</dt>
                                <dd class="col-sm-8">Tecnicatura Superior en Realización Audiovisual</dd>

                                <dt class="col-sm-4">Requisitos:</dt>
                                <dd class="col-sm-8">Nivel Medio Aprobado + Entrevista vocacional</dd>

                                <dt class="col-sm-4">Duración:</dt>
                                <dd class="col-sm-8">2 años y medio/ 5 cuatrimestres</dd>

                                <dt class="col-sm-4">Cantidad de horas:</dt>
                                <dd class="col-sm-8">2104 horas cátedra</dd>

                                <dt class="col-sm-4">Enfoque:</dt>
                                <dd class="col-sm-8">Tecnología Digital y Universo Audiovisual</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light h-100">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fa fa-tools"></i>
                                Perfil del/a egresado/a:
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <span>
                                        Podrás planificar la producción y ejecutar la realización de
                                        un producto audiovisual. Desarrollar la producción de una pieza audiovisual en sus
                                        aspectos artísticos y técnicos, tanto en inicio como durante el registro del material
                                        audiovisual; de participar y planificar los procesos de postproducción. Asimismo,
                                        podrás evaluar las posibilidades y variables socioeconómicas que influyen en la
                                        realización del producto audiovisual.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filosofía educativa -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fa fa-lightbulb text-warning"></i>
                        Filosofía Educativa - Visión Integral
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <!-- Fundamentacion -->
                            <h6 class="text-primary">Fundamentacion:</h6>
                            <p class="lead">
                                <?php echo htmlspecialchars($_ENV['CARRERA_DESCRIPTION'] ?? ''); ?>
                            </p>
                            <!-- Objetivo -->
                            </div>
                            <div class="col-md-7">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-primary">Objetivo:</h6>
                                    <ul class="mb-0">
                                        <li>Describir las características de la TV digital como parte del universo audiovisual.</li>
                                        <li>Reconocer los factores que influyen en la dinámica de la TV digital.</li>
                                        <li>Identificar y caracterizar formatos tradicionales y nuevos de formas de desarrollo,
                                            producción y comercialización de productos audiovisuales en la era digital.</li>
                                        <li>Realizar investigaciones de mercado y de campo.</li>
                                        <li>Describir los modelos de financiamiento tradicionales y alternativos.</li>
                                        <li>Elaborar un plan de marketing para una serie web.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perfil del Egresado -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fa fa-user-graduate text-success"></i>
                            Perfil del Egresado
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-danger">Como Realizador:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Dirigir la puesta en escena televisiva
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Coordinar equipos técnicos y artísticos
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Desarrollar conceptos creativos innovadores
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Adaptar contenidos a nuevos formatos
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Como Productor:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Gestionar proyectos audiovisuales integrales
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Administrar recursos y presupuestos
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Negociar contratos y derechos
                                    </li>
                                    <li class="list-group-item border-0 px-0">
                                        <i class="fa fa-check text-success me-2"></i>
                                        Identificar oportunidades de mercado
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plan de Estudios -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fa fa-graduation-cap text-info"></i>
                            Plan de Estudios - Enfoque Práctico
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Primer Año -->
                            <div class="col-md-4 mb-3">
                                <h5 class="text-danger border-bottom pb-2">
                                    <i class="fa fa-play-circle"></i>
                                    Primer Año - Fundamentos
                                </h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-video text-danger me-2"></i>
                                        Introducción a la TV Digital
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-cut text-primary me-2"></i>
                                        Edición y Postproducción I
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-camera text-success me-2"></i>
                                        Lenguaje Audiovisual
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-history text-warning me-2"></i>
                                        Historia de los Medios
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-pen text-info me-2"></i>
                                        Guión Televisivo
                                    </li>
                                </ul>
                            </div>

                            <!-- Segundo Año -->
                            <div class="col-md-4 mb-3">
                                <h5 class="text-warning border-bottom pb-2">
                                    <i class="fa fa-cogs"></i>
                                    Segundo Año - Producción
                                </h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-tv text-danger me-2"></i>
                                        Producción Televisiva I
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-microphone text-primary me-2"></i>
                                        Audio y Sonido Digital
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-magic text-success me-2"></i>
                                        Efectos Especiales
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-chart-line text-warning me-2"></i>
                                        Marketing Audiovisual
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-balance-scale text-info me-2"></i>
                                        Aspectos Legales
                                    </li>
                                </ul>
                            </div>

                            <!-- Tercer Año -->
                            <div class="col-md-4 mb-3">
                                <h5 class="text-success border-bottom pb-2">
                                    <i class="fa fa-rocket"></i>
                                    Tercer Año - Especialización
                                </h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-broadcast-tower text-danger me-2"></i>
                                        Producción Televisiva II
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-users text-primary me-2"></i>
                                        Dirección de Equipos
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-wifi text-success me-2"></i>
                                        Transmedia y Streaming
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-briefcase text-warning me-2"></i>
                                        Gestión de Proyectos
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fa fa-star text-info me-2"></i>
                                        Trabajo Final Integrador
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instalaciones -->
                <div class="card mb-4">
                    <div class="card-header bg-gray-dark">
                        <h4 class="mb-0">
                            <i class="fa fa-building"></i>
                            Instalaciones y Estudios - IFTS15
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>🎬 Estudio de TV</h6>
                                <ul class="small">
                                    <li>Set de grabación profesional</li>
                                    <li>Sistema de iluminación LED</li>
                                    <li>Cámaras 4K con teleprompter</li>
                                    <li>Mesa de mezclas de audio</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>💻 Sala de Edición</h6>
                                <ul class="small">
                                    <li>Estaciones con software profesional</li>
                                    <li>Monitores calibrados para color</li>
                                    <li>Sistemas de almacenamiento SAN</li>
                                    <li>Equipos de masterización</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo laboral -->
                <div class="card mb-4">
                    <div class="card-header bg-gray-info">
                        <h4 class="mb-0">
                            <i class="fa fa-briefcase"></i>
                            Oportunidades Profesionales
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-danger">Medios Tradicionales:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-check text-success me-2"></i>Canales de TV abierta y cable</li>
                                    <li><i class="fa fa-check text-success me-2"></i>Productoras audiovisuales</li>
                                    <li><i class="fa fa-check text-success me-2"></i>Estudios de grabación</li>
                                    <li><i class="fa fa-check text-success me-2"></i>Agencias de publicidad</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Nuevos Medios:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-check text-success me-2"></i>Plataformas de streaming</li>
                                    <li><i class="fa fa-check text-success me-2"></i>Contenido para redes sociales</li>
                                    <li><i class="fa fa-check text-success me-2"></i>Producción independiente</li>
                                    <li><i class="fa fa-check text-success me-2"></i>Consultoría en medios digitales</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action específico IFTS15 -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card cta-card text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-3">🎬 ¡Comienza tu carrera en TV!</h3>
                                <p class="lead mb-4">
                                    Únete al IFTS15 y forma parte de la nueva generación de realizadores y productores televisivos
                                </p>
                                <div class="btn-group btn-group-rounded" role="group">
                                    <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
                                        <i class="fa fa-user-plus text-dark"></i>
                                        Inscribirse Ahora
                                    </button>
                                    <button class="btn btn-dark btn-lg" data-bs-toggle="modal" data-bs-target="#consultasModal">
                                        <i class="fa fa-phone text-warning"></i>
                                        Contactar
                                    </button>
                                    <a href="<?php echo BASE_URL; ?>/" class="btn btn-warning btn-lg">
                                        <i class="fa fa-home text-dark"></i>
                                        Volver al Inicio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Incluir footer del sistema -->
    <?php include __DIR__ . '/../Template/footer.php'; ?>

    </body>

    </html>