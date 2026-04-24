<?php

namespace App\Model;

use mysqli;

class SiteCustomizationModel
{
    private static bool $tablesReady = false;

    private static function defaultNavbar(): array
    {
        return [
            'id_navbar' => null,
            'brand_text' => 'IFTS15',
            'logo_url' => null,
            'logo_public_id' => null,
            'habilitado' => 1,
        ];
    }

    private static function defaultSidebar(): array
    {
        return [
            'id_sidebar' => null,
            'brand_text' => 'Panel de Usuario',
            'logo_url' => null,
            'logo_public_id' => null,
            'habilitado' => 1,
        ];
    }

    private static function defaultFooter(): array
    {
        return [
            'id_footer' => null,
            'credit_text' => 'Desarrollado por Les muchaches del Inap',
            'credit_url' => 'https://github.com/DeMentes-Consultora/IFTS15',
            'logo_url' => null,
            'logo_public_id' => null,
            'habilitado' => 1,
        ];
    }

    private static function ensureTables(mysqli $conn): void
    {
        if (self::$tablesReady) {
            return;
        }

        $conn->query(
            "CREATE TABLE IF NOT EXISTS site_navbar (
                id_navbar INT(11) NOT NULL AUTO_INCREMENT,
                brand_text VARCHAR(255) NOT NULL DEFAULT 'IFTS15',
                logo_url TEXT NULL,
                logo_public_id VARCHAR(255) NULL,
                habilitado TINYINT(1) NOT NULL DEFAULT 1,
                cancelado TINYINT(1) NOT NULL DEFAULT 0,
                idCreate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                idUpdate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id_navbar)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $conn->query(
            "CREATE TABLE IF NOT EXISTS site_sidebar (
                id_sidebar INT(11) NOT NULL AUTO_INCREMENT,
                brand_text VARCHAR(255) NOT NULL DEFAULT 'Panel de Usuario',
                logo_url TEXT NULL,
                logo_public_id VARCHAR(255) NULL,
                habilitado TINYINT(1) NOT NULL DEFAULT 1,
                cancelado TINYINT(1) NOT NULL DEFAULT 0,
                idCreate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                idUpdate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id_sidebar)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $conn->query(
            "CREATE TABLE IF NOT EXISTS site_carousel (
                id_slide INT(11) NOT NULL AUTO_INCREMENT,
                titulo VARCHAR(255) NOT NULL DEFAULT '',
                descripcion TEXT NULL,
                link_url VARCHAR(255) NULL,
                orden_visual INT(11) NOT NULL DEFAULT 1,
                image_url TEXT NULL,
                image_public_id VARCHAR(255) NULL,
                habilitado TINYINT(1) NOT NULL DEFAULT 1,
                cancelado TINYINT(1) NOT NULL DEFAULT 0,
                idCreate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                idUpdate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id_slide)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $conn->query(
            "CREATE TABLE IF NOT EXISTS site_footer (
                id_footer INT(11) NOT NULL AUTO_INCREMENT,
                credit_text VARCHAR(255) NOT NULL DEFAULT 'Desarrollado por Les muchaches del Inap',
                credit_url VARCHAR(500) NULL,
                logo_url TEXT NULL,
                logo_public_id VARCHAR(255) NULL,
                habilitado TINYINT(1) NOT NULL DEFAULT 1,
                cancelado TINYINT(1) NOT NULL DEFAULT 0,
                idCreate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                idUpdate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id_footer)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        // Migracion para instalaciones existentes
        $creditUrlColumn = $conn->query("SHOW COLUMNS FROM site_footer LIKE 'credit_url'");
        if (!$creditUrlColumn || (int)$creditUrlColumn->num_rows === 0) {
            $conn->query("ALTER TABLE site_footer ADD COLUMN credit_url VARCHAR(500) NULL AFTER credit_text");
        }

        self::$tablesReady = true;
    }

    public static function getNavbar(?mysqli $conn): array
    {
        if (!$conn instanceof mysqli) {
            return self::defaultNavbar();
        }

        self::ensureTables($conn);

        $sql = "SELECT id_navbar, brand_text, logo_url, logo_public_id, habilitado
                FROM site_navbar
                WHERE cancelado = 0
                ORDER BY id_navbar ASC
                LIMIT 1";

        $result = $conn->query($sql);
        $row = $result ? $result->fetch_assoc() : null;

        return [
            'id_navbar' => isset($row['id_navbar']) ? (int)$row['id_navbar'] : null,
            'brand_text' => trim((string)($row['brand_text'] ?? self::defaultNavbar()['brand_text'])),
            'logo_url' => self::nullableText($row['logo_url'] ?? null),
            'logo_public_id' => self::nullableText($row['logo_public_id'] ?? null),
            'habilitado' => isset($row['habilitado']) ? (int)$row['habilitado'] : self::defaultNavbar()['habilitado'],
        ];
    }

    public static function saveNavbar(mysqli $conn, array $navbar): array
    {
        self::ensureTables($conn);

        $current = self::getNavbar($conn);
        $brandText = trim((string)($navbar['brand_text'] ?? 'IFTS15'));
        $logoUrl = self::nullableText($navbar['logo_url'] ?? null);
        $logoPublicId = self::nullableText($navbar['logo_public_id'] ?? null);
        $enabled = isset($navbar['habilitado']) ? (int)((bool)$navbar['habilitado']) : 1;

        if (!empty($current['id_navbar'])) {
            $stmt = $conn->prepare(
                "UPDATE site_navbar
                 SET brand_text = ?, logo_url = ?, logo_public_id = ?, habilitado = ?, cancelado = 0
                 WHERE id_navbar = ?"
            );
            $id = (int)$current['id_navbar'];
            $stmt->bind_param('sssii', $brandText, $logoUrl, $logoPublicId, $enabled, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO site_navbar (brand_text, logo_url, logo_public_id, habilitado, cancelado)
                 VALUES (?, ?, ?, ?, 0)"
            );
            $stmt->bind_param('sssi', $brandText, $logoUrl, $logoPublicId, $enabled);
            $stmt->execute();
            $stmt->close();
        }

        return self::getNavbar($conn);
    }

    public static function getSidebar(?mysqli $conn): array
    {
        if (!$conn instanceof mysqli) {
            return self::defaultSidebar();
        }

        self::ensureTables($conn);

        $sql = "SELECT id_sidebar, brand_text, logo_url, logo_public_id, habilitado
                FROM site_sidebar
                WHERE cancelado = 0
                ORDER BY id_sidebar ASC
                LIMIT 1";

        $result = $conn->query($sql);
        $row = $result ? $result->fetch_assoc() : null;

        return [
            'id_sidebar' => isset($row['id_sidebar']) ? (int)$row['id_sidebar'] : null,
            'brand_text' => trim((string)($row['brand_text'] ?? self::defaultSidebar()['brand_text'])),
            'logo_url' => self::nullableText($row['logo_url'] ?? null),
            'logo_public_id' => self::nullableText($row['logo_public_id'] ?? null),
            'habilitado' => isset($row['habilitado']) ? (int)$row['habilitado'] : self::defaultSidebar()['habilitado'],
        ];
    }

    public static function saveSidebar(mysqli $conn, array $sidebar): array
    {
        self::ensureTables($conn);

        $current = self::getSidebar($conn);
        $brandText = trim((string)($sidebar['brand_text'] ?? 'Panel de Usuario'));
        $logoUrl = self::nullableText($sidebar['logo_url'] ?? null);
        $logoPublicId = self::nullableText($sidebar['logo_public_id'] ?? null);
        $enabled = isset($sidebar['habilitado']) ? (int)((bool)$sidebar['habilitado']) : 1;

        if (!empty($current['id_sidebar'])) {
            $stmt = $conn->prepare(
                "UPDATE site_sidebar
                 SET brand_text = ?, logo_url = ?, logo_public_id = ?, habilitado = ?, cancelado = 0
                 WHERE id_sidebar = ?"
            );
            $id = (int)$current['id_sidebar'];
            $stmt->bind_param('sssii', $brandText, $logoUrl, $logoPublicId, $enabled, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO site_sidebar (brand_text, logo_url, logo_public_id, habilitado, cancelado)
                 VALUES (?, ?, ?, ?, 0)"
            );
            $stmt->bind_param('sssi', $brandText, $logoUrl, $logoPublicId, $enabled);
            $stmt->execute();
            $stmt->close();
        }

        return self::getSidebar($conn);
    }

    public static function getCarousel(?mysqli $conn, bool $includeDisabled = false): array
    {
        if (!$conn instanceof mysqli) {
            return [];
        }

        self::ensureTables($conn);

        $sql = "SELECT id_slide, titulo, descripcion, link_url, orden_visual, image_url, image_public_id, habilitado
                FROM site_carousel
                WHERE cancelado = 0";

        if (!$includeDisabled) {
            $sql .= " AND habilitado = 1";
        }

        $sql .= " ORDER BY orden_visual ASC, id_slide ASC";

        $result = $conn->query($sql);
        $slides = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $slides[] = [
                    'id_slide' => (int)$row['id_slide'],
                    'titulo' => trim((string)($row['titulo'] ?? '')),
                    'descripcion' => trim((string)($row['descripcion'] ?? '')),
                    'link_url' => self::nullableText($row['link_url'] ?? null),
                    'orden_visual' => (int)($row['orden_visual'] ?? 0),
                    'image_url' => self::nullableText($row['image_url'] ?? null),
                    'image_public_id' => self::nullableText($row['image_public_id'] ?? null),
                    'habilitado' => isset($row['habilitado']) ? (int)$row['habilitado'] : 1,
                ];
            }
        }

        return $slides;
    }

    public static function saveCarousel(mysqli $conn, array $slides): array
    {
        self::ensureTables($conn);

        $existing = self::getCarousel($conn, true);
        $existingIds = [];
        foreach ($existing as $item) {
            $existingIds[(int)$item['id_slide']] = true;
        }

        $persistedIds = [];

        foreach ($slides as $index => $slide) {
            $id = isset($slide['id_slide']) ? (int)$slide['id_slide'] : 0;
            $titulo = trim((string)($slide['titulo'] ?? ''));
            $descripcion = trim((string)($slide['descripcion'] ?? ''));
            $linkUrl = self::nullableText($slide['link_url'] ?? null);
            $order = isset($slide['orden_visual']) ? (int)$slide['orden_visual'] : ($index + 1);
            $imageUrl = self::nullableText($slide['image_url'] ?? null);
            $imagePublicId = self::nullableText($slide['image_public_id'] ?? null);
            $enabled = isset($slide['habilitado']) ? (int)((bool)$slide['habilitado']) : 1;

            if ($id > 0 && isset($existingIds[$id])) {
                $stmt = $conn->prepare(
                    "UPDATE site_carousel
                     SET titulo = ?, descripcion = ?, link_url = ?, orden_visual = ?, image_url = ?, image_public_id = ?, habilitado = ?, cancelado = 0
                     WHERE id_slide = ?"
                );
                $stmt->bind_param('sssissii', $titulo, $descripcion, $linkUrl, $order, $imageUrl, $imagePublicId, $enabled, $id);
                $stmt->execute();
                $stmt->close();
                $persistedIds[] = $id;
                continue;
            }

            $stmt = $conn->prepare(
                "INSERT INTO site_carousel (titulo, descripcion, link_url, orden_visual, image_url, image_public_id, habilitado, cancelado)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0)"
            );
            $stmt->bind_param('sssissi', $titulo, $descripcion, $linkUrl, $order, $imageUrl, $imagePublicId, $enabled);
            $stmt->execute();
            $persistedIds[] = (int)$conn->insert_id;
            $stmt->close();
        }

        foreach (array_keys($existingIds) as $existingId) {
            if (in_array($existingId, $persistedIds, true)) {
                continue;
            }

            $stmt = $conn->prepare("UPDATE site_carousel SET cancelado = 1 WHERE id_slide = ?");
            $stmt->bind_param('i', $existingId);
            $stmt->execute();
            $stmt->close();
        }

        return self::getCarousel($conn, true);
    }

    public static function getPublicConfig(?mysqli $conn): array
    {
        return [
            'navbar' => self::getNavbar($conn),
            'sidebar' => self::getSidebar($conn),
            'carousel' => self::getCarousel($conn, false),
            'footer' => self::getFooter($conn),
        ];
    }

    public static function getFooter(?mysqli $conn): array
    {
        if (!$conn instanceof mysqli) {
            return self::defaultFooter();
        }

        self::ensureTables($conn);

        $sql = "SELECT id_footer, credit_text, credit_url, logo_url, logo_public_id, habilitado
                FROM site_footer
                WHERE cancelado = 0
                ORDER BY id_footer ASC
                LIMIT 1";

        $result = $conn->query($sql);
        $row = $result ? $result->fetch_assoc() : null;

        return [
            'id_footer' => isset($row['id_footer']) ? (int)$row['id_footer'] : null,
            'credit_text' => trim((string)($row['credit_text'] ?? self::defaultFooter()['credit_text'])),
            'credit_url' => self::nullableText($row['credit_url'] ?? self::defaultFooter()['credit_url']),
            'logo_url' => self::nullableText($row['logo_url'] ?? null),
            'logo_public_id' => self::nullableText($row['logo_public_id'] ?? null),
            'habilitado' => isset($row['habilitado']) ? (int)$row['habilitado'] : self::defaultFooter()['habilitado'],
        ];
    }

    public static function saveFooter(mysqli $conn, array $footer): array
    {
        self::ensureTables($conn);

        $current = self::getFooter($conn);
        $creditText = trim((string)($footer['credit_text'] ?? 'Desarrollado por Les muchaches del Inap'));
        $creditUrl = self::nullableText($footer['credit_url'] ?? self::defaultFooter()['credit_url']);
        $logoUrl = self::nullableText($footer['logo_url'] ?? null);
        $logoPublicId = self::nullableText($footer['logo_public_id'] ?? null);
        $enabled = isset($footer['habilitado']) ? (int)((bool)$footer['habilitado']) : 1;

        if (!empty($current['id_footer'])) {
            $stmt = $conn->prepare(
                "UPDATE site_footer
                 SET credit_text = ?, credit_url = ?, logo_url = ?, logo_public_id = ?, habilitado = ?, cancelado = 0
                 WHERE id_footer = ?"
            );
            $id = (int)$current['id_footer'];
            $stmt->bind_param('ssssii', $creditText, $creditUrl, $logoUrl, $logoPublicId, $enabled, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO site_footer (credit_text, credit_url, logo_url, logo_public_id, habilitado, cancelado)
                 VALUES (?, ?, ?, ?, ?, 0)"
            );
            $stmt->bind_param('ssssi', $creditText, $creditUrl, $logoUrl, $logoPublicId, $enabled);
            $stmt->execute();
            $stmt->close();
        }

        return self::getFooter($conn);
    }

    private static function nullableText($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $text = trim((string)$value);
        return $text === '' ? null : $text;
    }
}
