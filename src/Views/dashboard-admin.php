<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/SiteCustomizationModel.php';

use App\ConectionBD\ConectionDB;
use App\Model\SiteCustomizationModel;

if (!isLoggedIn() || !canManageSiteCustomization()) {
    header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
    exit;
}

$conectarDB = new ConectionDB();
$conn = $conectarDB->getConnection();

$navbar = SiteCustomizationModel::getNavbar($conn);
$sidebar = SiteCustomizationModel::getSidebar($conn);
$footer = SiteCustomizationModel::getFooter($conn);
$carousel = SiteCustomizationModel::getCarousel($conn, true);
$isSuperAdmin = function_exists('getUserRoleId') && getUserRoleId() === 5;

$carouselRows = $carousel;

if (empty($carouselRows)) {
    $carouselRows[] = [
        'id_slide' => null,
        'titulo' => '',
        'descripcion' => '',
        'link_url' => null,
        'orden_visual' => 1,
        'image_url' => null,
        'image_public_id' => null,
        'habilitado' => 1,
    ];
}

$nextSlideIndex = count($carouselRows);

while (count($carouselRows) < 3) {
    $carouselRows[] = [
        'id_slide' => null,
        'titulo' => '',
        'descripcion' => '',
        'link_url' => null,
        'orden_visual' => count($carouselRows) + 1,
        'image_url' => null,
        'image_public_id' => null,
        'habilitado' => 1,
    ];
}

$pageTitle = 'Dashboard de Personalizacion - IFTS15';
?>

<?php include __DIR__ . '/../Template/head.php'; ?>
<?php include __DIR__ . '/../Template/navBar.php'; ?>
<?php include __DIR__ . '/../Template/sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/dashboard-admin.css">

<div class="container mt-5 pt-4 dashboard-admin-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-layout-text-window-reverse me-2"></i> Dashboard de Personalizacion</h2>
            <p class="text-muted mb-0">Gestion de imagenes del navbar, sidebar y carrusel principal.</p>
        </div>
    </div>

    <?php if (!empty($_SESSION['customization_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($_SESSION['customization_success'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['customization_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['customization_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($_SESSION['customization_error'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['customization_error']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-badge-ad me-2"></i>Navbar</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>/src/Controllers/siteCustomizationController.php" enctype="multipart/form-data">
                        <input type="hidden" name="section" value="navbar">

                        <div class="mb-3">
                            <label class="form-label">Texto de marca</label>
                            <input type="text" class="form-control" name="brand_text" value="<?php echo htmlspecialchars((string)$navbar['brand_text'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Logo actual</label>
                            <div class="logo-preview-box">
                                <?php if (!empty($navbar['logo_url'])): ?>
                                    <img src="<?php echo htmlspecialchars((string)$navbar['logo_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Logo navbar" class="preview-logo">
                                <?php else: ?>
                                    <span class="text-muted">Sin logo personalizado</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subir nuevo logo</label>
                            <input type="file" class="form-control" name="logo_file" accept="image/*">
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo_navbar" value="1">
                            <label class="form-check-label" for="remove_logo_navbar">Quitar logo actual</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="navbar_enabled" id="navbar_enabled" value="1" <?php echo !empty($navbar['habilitado']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="navbar_enabled">Navbar personalizado habilitado</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i>Guardar Navbar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-columns-reverse me-2"></i>Sidebar</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>/src/Controllers/siteCustomizationController.php" enctype="multipart/form-data">
                        <input type="hidden" name="section" value="sidebar">

                        <div class="mb-3">
                            <label class="form-label">Texto de cabecera</label>
                            <input type="text" class="form-control" name="sidebar_brand_text" value="<?php echo htmlspecialchars((string)$sidebar['brand_text'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Logo actual</label>
                            <div class="logo-preview-box">
                                <?php if (!empty($sidebar['logo_url'])): ?>
                                    <img src="<?php echo htmlspecialchars((string)$sidebar['logo_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Logo sidebar" class="preview-logo">
                                <?php else: ?>
                                    <span class="text-muted">Sin logo personalizado</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subir nuevo logo</label>
                            <input type="file" class="form-control" name="sidebar_logo_file" accept="image/*">
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_sidebar_logo" id="remove_logo_sidebar" value="1">
                            <label class="form-check-label" for="remove_logo_sidebar">Quitar logo actual</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="sidebar_enabled" id="sidebar_enabled" value="1" <?php echo !empty($sidebar['habilitado']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="sidebar_enabled">Sidebar personalizado habilitado</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i>Guardar Sidebar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($isSuperAdmin): ?>
        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-credit-card-2-front me-2"></i>Footer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>/src/Controllers/siteCustomizationController.php" enctype="multipart/form-data">
                        <input type="hidden" name="section" value="footer">

                        <div class="mb-3">
                            <label class="form-label">Texto de crédito</label>
                            <input type="text" class="form-control" name="footer_credit_text" value="<?php echo htmlspecialchars((string)$footer['credit_text'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Logo actual</label>
                            <div class="logo-preview-box">
                                <?php if (!empty($footer['logo_url'])): ?>
                                    <img src="<?php echo htmlspecialchars((string)$footer['logo_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Logo footer" class="preview-logo">
                                <?php else: ?>
                                    <span class="text-muted">Sin logo personalizado</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subir logo</label>
                            <input type="file" class="form-control" name="footer_logo_file" accept="image/*">
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_footer_logo" id="remove_logo_footer" value="1">
                            <label class="form-check-label" for="remove_logo_footer">Quitar logo actual</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="footer_enabled" id="footer_enabled" value="1" <?php echo !empty($footer['habilitado']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="footer_enabled">Footer personalizado habilitado</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i>Guardar Footer
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-images me-2"></i>Carrusel Principal</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>/src/Controllers/siteCustomizationController.php" enctype="multipart/form-data">
                        <input type="hidden" name="section" value="carousel">

                        <div class="row g-4" id="carousel-slides-container" data-next-index="<?php echo (int)$nextSlideIndex; ?>">
                            <?php foreach ($carouselRows as $index => $slide): ?>
                                <div class="col-12 col-lg-4">
                                    <div class="slide-card" data-slide-index="<?php echo $index; ?>">
                                        <h6 class="mb-3">Slide <?php echo $index + 1; ?></h6>
                                        <input type="hidden" name="slide_id[<?php echo $index; ?>]" value="<?php echo isset($slide['id_slide']) ? (int)$slide['id_slide'] : ''; ?>">

                                        <div class="mb-2">
                                            <label class="form-label">Titulo</label>
                                            <input type="text" class="form-control" name="slide_title[<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)($slide['titulo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Descripcion</label>
                                            <textarea class="form-control" rows="2" name="slide_description[<?php echo $index; ?>]"><?php echo htmlspecialchars((string)($slide['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Enlace (opcional)</label>
                                            <input type="text" class="form-control" name="slide_link[<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)($slide['link_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Imagen actual</label>
                                            <div class="slide-preview-box">
                                                <?php if (!empty($slide['image_url'])): ?>
                                                    <img src="<?php echo htmlspecialchars((string)$slide['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Slide <?php echo $index + 1; ?>" class="preview-slide">
                                                <?php else: ?>
                                                    <span class="text-muted">Sin imagen</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Subir nueva imagen</label>
                                            <input type="file" class="form-control" name="slide_image_<?php echo $index; ?>" accept="image/*">
                                        </div>

                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="remove_slide_image[<?php echo $index; ?>]" id="remove_slide_image_<?php echo $index; ?>" value="1">
                                            <label class="form-check-label" for="remove_slide_image_<?php echo $index; ?>">Quitar imagen actual</label>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="slide_enabled[<?php echo $index; ?>]" id="slide_enabled_<?php echo $index; ?>" value="1" <?php echo !empty($slide['habilitado']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slide_enabled_<?php echo $index; ?>">Slide habilitado</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="delete_slide[<?php echo $index; ?>]" id="delete_slide_<?php echo $index; ?>" value="1">
                                            <label class="form-check-label text-danger" for="delete_slide_<?php echo $index; ?>">Eliminar slide</label>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary" id="add-carousel-slide">
                                <i class="bi bi-plus-circle me-2"></i>Agregar slide
                            </button>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-save me-2"></i>Guardar Carrusel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="carousel-slide-template">
    <div class="col-12 col-lg-4">
        <div class="slide-card" data-slide-index="__INDEX__">
            <h6 class="mb-3">Slide __HUMAN_INDEX__</h6>
            <input type="hidden" name="slide_id[__INDEX__]" value="">

            <div class="mb-2">
                <label class="form-label">Titulo</label>
                <input type="text" class="form-control" name="slide_title[__INDEX__]" value="">
            </div>

            <div class="mb-2">
                <label class="form-label">Descripcion</label>
                <textarea class="form-control" rows="2" name="slide_description[__INDEX__]"></textarea>
            </div>

            <div class="mb-2">
                <label class="form-label">Enlace (opcional)</label>
                <input type="text" class="form-control" name="slide_link[__INDEX__]" value="">
            </div>

            <div class="mb-2">
                <label class="form-label">Imagen actual</label>
                <div class="slide-preview-box">
                    <span class="text-muted">Sin imagen</span>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Subir nueva imagen</label>
                <input type="file" class="form-control" name="slide_image___INDEX__" accept="image/*">
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="remove_slide_image[__INDEX__]" id="remove_slide_image___INDEX__" value="1">
                <label class="form-check-label" for="remove_slide_image___INDEX__">Quitar imagen actual</label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="slide_enabled[__INDEX__]" id="slide_enabled___INDEX__" value="1" checked>
                <label class="form-check-label" for="slide_enabled___INDEX__">Slide habilitado</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="delete_slide[__INDEX__]" id="delete_slide___INDEX__" value="1">
                <label class="form-check-label text-danger" for="delete_slide___INDEX__">Eliminar slide</label>
            </div>
        </div>
    </div>
</template>

<script>
    (function () {
        var container = document.getElementById('carousel-slides-container');
        var addButton = document.getElementById('add-carousel-slide');
        var template = document.getElementById('carousel-slide-template');

        if (!container || !addButton || !template) {
            return;
        }

        addButton.addEventListener('click', function () {
            var nextIndex = parseInt(container.getAttribute('data-next-index') || '0', 10);
            var html = template.innerHTML
                .replace(/__INDEX__/g, String(nextIndex))
                .replace(/__HUMAN_INDEX__/g, String(nextIndex + 1));

            container.insertAdjacentHTML('beforeend', html);
            container.setAttribute('data-next-index', String(nextIndex + 1));
        });
    })();
</script>

<?php include __DIR__ . '/../Template/footer.php'; ?>
