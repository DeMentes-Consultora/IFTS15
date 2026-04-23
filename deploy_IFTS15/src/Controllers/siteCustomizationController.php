<?php

namespace App\Controllers;

use App\ConectionBD\ConectionDB;
use App\Model\SiteCustomizationModel;
use App\Services\CloudinaryService;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/SiteCustomizationModel.php';
require_once __DIR__ . '/../services/CloudinaryService.php';

if (!isLoggedIn() || !canManageSiteCustomization()) {
    header('Location: ' . BASE_URL . '/index.php?error=acceso_denegado');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/src/Controllers/viewController.php?view=dashboard-admin');
    exit;
}

$section = trim((string)($_POST['section'] ?? ''));
if ($section === '') {
    $_SESSION['customization_error'] = 'Seccion de personalizacion invalida.';
    header('Location: ' . BASE_URL . '/src/Controllers/viewController.php?view=dashboard-admin');
    exit;
}

try {
    $db = new ConectionDB();
    $conn = $db->getConnection();

    switch ($section) {
        case 'navbar':
            handleNavbar($conn);
            $_SESSION['customization_success'] = 'Navbar actualizado correctamente.';
            break;

        case 'sidebar':
            handleSidebar($conn);
            $_SESSION['customization_success'] = 'Sidebar actualizado correctamente.';
            break;

        case 'carousel':
            handleCarousel($conn);
            $_SESSION['customization_success'] = 'Carrusel actualizado correctamente.';
            break;

        case 'footer':
            if (!function_exists('getUserRoleId') || getUserRoleId() !== 5) {
                $_SESSION['customization_error'] = 'Solo Administrador puede gestionar el footer.';
                break;
            }
            handleFooter($conn);
            $_SESSION['customization_success'] = 'Footer actualizado correctamente.';
            break;

        default:
            $_SESSION['customization_error'] = 'Seccion no soportada.';
            break;
    }
} catch (\Throwable $e) {
    error_log('siteCustomizationController error: ' . $e->getMessage());
    $_SESSION['customization_error'] = DEBUG_MODE
        ? ('Error: ' . $e->getMessage())
        : 'No se pudo guardar la personalizacion.';
}

header('Location: ' . BASE_URL . '/src/Controllers/viewController.php?view=dashboard-admin');
exit;

function handleNavbar($conn): void
{
    $current = SiteCustomizationModel::getNavbar($conn);
    $brandText = trim((string)($_POST['brand_text'] ?? 'IFTS15'));
    $enabled = isset($_POST['navbar_enabled']) ? 1 : 0;

    $logoUrl = $current['logo_url'] ?? null;
    $logoPublicId = $current['logo_public_id'] ?? null;

    if (isset($_POST['remove_logo']) && !empty($logoPublicId)) {
        $cloudinary = new CloudinaryService();
        $cloudinary->deleteImage($logoPublicId);
        $logoUrl = null;
        $logoPublicId = null;
    }

    if (!empty($_FILES['logo_file']) && (int)$_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
        $cloudinary = new CloudinaryService();
        $upload = $cloudinary->uploadImage($_FILES['logo_file']['tmp_name'], $_FILES['logo_file']['name'], 'ifts15/navbar');

        if (!empty($logoPublicId) && $logoPublicId !== ($upload['public_id'] ?? null)) {
            $cloudinary->deleteImage($logoPublicId);
        }

        $logoUrl = $upload['secure_url'] ?? ($upload['url'] ?? null);
        $logoPublicId = $upload['public_id'] ?? null;
    }

    SiteCustomizationModel::saveNavbar($conn, [
        'brand_text' => $brandText,
        'logo_url' => $logoUrl,
        'logo_public_id' => $logoPublicId,
        'habilitado' => $enabled,
    ]);
}

function handleSidebar($conn): void
{
    $current = SiteCustomizationModel::getSidebar($conn);
    $brandText = trim((string)($_POST['sidebar_brand_text'] ?? 'Panel de Usuario'));
    $enabled = isset($_POST['sidebar_enabled']) ? 1 : 0;

    $logoUrl = $current['logo_url'] ?? null;
    $logoPublicId = $current['logo_public_id'] ?? null;

    if (isset($_POST['remove_sidebar_logo']) && !empty($logoPublicId)) {
        $cloudinary = new CloudinaryService();
        $cloudinary->deleteImage($logoPublicId);
        $logoUrl = null;
        $logoPublicId = null;
    }

    if (!empty($_FILES['sidebar_logo_file']) && (int)$_FILES['sidebar_logo_file']['error'] === UPLOAD_ERR_OK) {
        $cloudinary = new CloudinaryService();
        $upload = $cloudinary->uploadImage($_FILES['sidebar_logo_file']['tmp_name'], $_FILES['sidebar_logo_file']['name'], 'ifts15/sidebar');

        if (!empty($logoPublicId) && $logoPublicId !== ($upload['public_id'] ?? null)) {
            $cloudinary->deleteImage($logoPublicId);
        }

        $logoUrl = $upload['secure_url'] ?? ($upload['url'] ?? null);
        $logoPublicId = $upload['public_id'] ?? null;
    }

    SiteCustomizationModel::saveSidebar($conn, [
        'brand_text' => $brandText,
        'logo_url' => $logoUrl,
        'logo_public_id' => $logoPublicId,
        'habilitado' => $enabled,
    ]);
}

function handleCarousel($conn): void
{
    $existingSlides = SiteCustomizationModel::getCarousel($conn, true);
    $existingMap = [];
    foreach ($existingSlides as $slide) {
        $existingMap[(int)$slide['id_slide']] = $slide;
    }

    $submitted = [];

    $indices = [];
    $postArrayFields = [
        'slide_id',
        'delete_slide',
        'slide_title',
        'slide_description',
        'slide_link',
        'slide_enabled',
        'remove_slide_image',
    ];

    foreach ($postArrayFields as $field) {
        if (!isset($_POST[$field]) || !is_array($_POST[$field])) {
            continue;
        }

        foreach (array_keys($_POST[$field]) as $key) {
            if (is_numeric($key)) {
                $indices[(int)$key] = true;
            }
        }
    }

    foreach (array_keys($_FILES) as $fileFieldName) {
        if (preg_match('/^slide_image_(\d+)$/', (string)$fileFieldName, $matches)) {
            $indices[(int)$matches[1]] = true;
        }
    }

    ksort($indices);
    $visualOrder = 1;

    foreach (array_keys($indices) as $i) {
        $id = isset($_POST['slide_id'][$i]) ? (int)$_POST['slide_id'][$i] : 0;
        $deleteSlide = isset($_POST['delete_slide'][$i]);
        $current = $id > 0 && isset($existingMap[$id]) ? $existingMap[$id] : null;

        if ($deleteSlide) {
            if (!empty($current['image_public_id'])) {
                $cloudinary = new CloudinaryService();
                $cloudinary->deleteImage($current['image_public_id']);
            }
            continue;
        }

        $title = trim((string)($_POST['slide_title'][$i] ?? ''));
        $description = trim((string)($_POST['slide_description'][$i] ?? ''));
        $linkUrl = trim((string)($_POST['slide_link'][$i] ?? ''));
        $enabled = isset($_POST['slide_enabled'][$i]) ? 1 : 0;

        $imageUrl = $current['image_url'] ?? null;
        $imagePublicId = $current['image_public_id'] ?? null;

        if (isset($_POST['remove_slide_image'][$i]) && !empty($imagePublicId)) {
            $cloudinary = new CloudinaryService();
            $cloudinary->deleteImage($imagePublicId);
            $imageUrl = null;
            $imagePublicId = null;
        }

        $fileField = 'slide_image_' . $i;
        if (!empty($_FILES[$fileField]) && (int)($_FILES[$fileField]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $cloudinary = new CloudinaryService();
            $upload = $cloudinary->uploadImage($_FILES[$fileField]['tmp_name'], $_FILES[$fileField]['name'], 'ifts15/carousel');

            if (!empty($imagePublicId) && $imagePublicId !== ($upload['public_id'] ?? null)) {
                $cloudinary->deleteImage($imagePublicId);
            }

            $imageUrl = $upload['secure_url'] ?? ($upload['url'] ?? null);
            $imagePublicId = $upload['public_id'] ?? null;
        }

        $isEmptyRow = ($title === '' && $description === '' && $linkUrl === '' && empty($imageUrl));
        if ($isEmptyRow && $id <= 0) {
            continue;
        }

        $submitted[] = [
            'id_slide' => $id > 0 ? $id : null,
            'titulo' => $title,
            'descripcion' => $description,
            'link_url' => $linkUrl !== '' ? $linkUrl : null,
            'orden_visual' => $visualOrder,
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'habilitado' => $enabled,
        ];

        $visualOrder++;
    }

    SiteCustomizationModel::saveCarousel($conn, $submitted);
}

function handleFooter($conn): void
{
    $current = SiteCustomizationModel::getFooter($conn);
    $creditText = trim((string)($_POST['footer_credit_text'] ?? 'Desarrollado por Les muchaches del Inap'));
    $enabled = isset($_POST['footer_enabled']) ? 1 : 0;

    $logoUrl = $current['logo_url'] ?? null;
    $logoPublicId = $current['logo_public_id'] ?? null;

    if (isset($_POST['remove_footer_logo']) && !empty($logoPublicId)) {
        $cloudinary = new CloudinaryService();
        $cloudinary->deleteImage($logoPublicId);
        $logoUrl = null;
        $logoPublicId = null;
    }

    if (!empty($_FILES['footer_logo_file']) && (int)($_FILES['footer_logo_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $cloudinary = new CloudinaryService();
        $upload = $cloudinary->uploadImage($_FILES['footer_logo_file']['tmp_name'], $_FILES['footer_logo_file']['name'], 'ifts15/footer');

        if (!empty($logoPublicId) && $logoPublicId !== ($upload['public_id'] ?? null)) {
            $cloudinary->deleteImage($logoPublicId);
        }

        $logoUrl = $upload['secure_url'] ?? ($upload['url'] ?? null);
        $logoPublicId = $upload['public_id'] ?? null;
    }

    SiteCustomizationModel::saveFooter($conn, [
        'credit_text' => $creditText,
        'logo_url' => $logoUrl,
        'logo_public_id' => $logoPublicId,
        'habilitado' => $enabled,
    ]);
}
