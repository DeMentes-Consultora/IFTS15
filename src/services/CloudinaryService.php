<?php
// src/services/CloudinaryService.php
namespace App\Services;

use Cloudinary\Cloudinary;
use Exception;

class CloudinaryService
{
    private $cloudinary;

    private function normalizeFileName(?string $fileName, string $defaultBase = 'archivo'): string
    {
        $fileName = basename(str_replace('\\', '/', (string)$fileName));
        $extension = strtolower((string)pathinfo($fileName, PATHINFO_EXTENSION));
        $baseName = (string)pathinfo($fileName, PATHINFO_FILENAME);

        $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $baseName);
        if ($transliterated !== false && $transliterated !== '') {
            $baseName = $transliterated;
        }

        $baseName = preg_replace('/[^A-Za-z0-9_-]+/', '-', $baseName) ?? '';
        $baseName = trim($baseName, '-_.');
        if ($baseName === '') {
            $baseName = $defaultBase;
        }

        $extension = preg_replace('/[^a-z0-9]+/', '', $extension) ?? '';

        return $extension !== '' ? $baseName . '.' . $extension : $baseName;
    }

    private function buildRawPublicId(string $fileName, string $defaultBase = 'cv'): string
    {
        $normalized = $this->normalizeFileName($fileName, $defaultBase);
        $extension = strtolower((string)pathinfo($normalized, PATHINFO_EXTENSION));
        $baseName = (string)pathinfo($normalized, PATHINFO_FILENAME);
        $suffix = substr(sha1($normalized . '|' . microtime(true) . '|' . mt_rand()), 0, 12);

        return $baseName . '-' . $suffix . ($extension !== '' ? '.' . $extension : '');
    }

    public function __construct()
    {
        $cloudName = trim((string)($_ENV['CLOUDINARY_CLOUD_NAME'] ?? ''));
        $apiKey = trim((string)($_ENV['CLOUDINARY_API_KEY'] ?? ''));
        $apiSecret = trim((string)($_ENV['CLOUDINARY_API_SECRET'] ?? ''));

        if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
            throw new Exception('Configuracion de Cloudinary incompleta. Defini CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY y CLOUDINARY_API_SECRET en el archivo .env');
        }

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
            ],
        ]);
    }

    /**
     * Sube una imagen a Cloudinary
     * @param string $fileTmpPath
     * @param string $fileName
     * @param string $folder
     * @param string|null $publicId
     * @return array
     * @throws Exception
     */
    public function uploadImage($fileTmpPath, $fileName, $folder = 'ifts15/perfiles', $publicId = null)
    {
        $publicId = $publicId ?: $folder . '/' . uniqid();
        $result = $this->cloudinary->uploadApi()->upload($fileTmpPath, [
            'public_id' => $publicId,
            'folder' => $folder,
            'overwrite' => true,
            'resource_type' => 'image',
            'use_filename' => true,
            'unique_filename' => false
        ]);
        return $result;
    }

    public function uploadRawFile($fileTmpPath, $fileName, $folder = 'ifts15/cv', $publicId = null)
    {
        $publicId = $publicId ?: $this->buildRawPublicId((string)$fileName, 'cv');
        $downloadName = $this->normalizeFileName((string)$fileName, 'cv');

        return $this->cloudinary->uploadApi()->upload($fileTmpPath, [
            'public_id' => $publicId,
            'folder' => $folder,
            'overwrite' => true,
            'resource_type' => 'raw',
            'use_filename' => false,
            'unique_filename' => false,
            'filename_override' => $downloadName,
        ]);
    }

    public function buildRawDownloadUrl(?string $publicId, ?string $fileName = null, ?string $fallbackUrl = null): ?string
    {
        $attachmentName = rawurlencode($this->normalizeFileName($fileName, 'cv'));

        if (is_string($fallbackUrl) && $fallbackUrl !== '' && strpos($fallbackUrl, '/upload/') !== false) {
            return preg_replace('#/upload/#', '/upload/fl_attachment:' . $attachmentName . '/', $fallbackUrl, 1) ?: $fallbackUrl;
        }

        if (!is_string($publicId) || $publicId === '') {
            return $fallbackUrl;
        }

        $cloudName = trim((string)($_ENV['CLOUDINARY_CLOUD_NAME'] ?? ''));
        if ($cloudName === '') {
            return $fallbackUrl;
        }

        return 'https://res.cloudinary.com/' . rawurlencode($cloudName) . '/raw/upload/fl_attachment:' . $attachmentName . '/' . ltrim($publicId, '/');
    }

    /**
     * Sube una foto de perfil y la normaliza a formato cuadrado optimizado.
     * @param string $fileTmpPath
     * @param string $fileName
     * @param string $folder
     * @param string|null $publicId
     * @return array
     * @throws Exception
     */
    public function uploadProfileImage($fileTmpPath, $fileName, $folder = 'ifts15/perfiles', $publicId = null)
    {
        $publicId = $publicId ?: $folder . '/' . uniqid();
        $result = $this->cloudinary->uploadApi()->upload($fileTmpPath, [
            'public_id' => $publicId,
            'folder' => $folder,
            'overwrite' => true,
            'resource_type' => 'image',
            'use_filename' => true,
            'unique_filename' => false,
            'transformation' => [
                [
                    'width' => 400,
                    'height' => 400,
                    'crop' => 'fill',
                    'gravity' => 'face',
                    'quality' => 'auto',
                    'fetch_format' => 'auto'
                ]
            ]
        ]);
        return $result;
    }

    /**
     * Elimina una imagen de Cloudinary por public_id
     * @param string $publicId
     * @return array
     */
    public function deleteImage($publicId, $resourceType = 'image')
    {
        return $this->cloudinary->uploadApi()->destroy($publicId, [
            'resource_type' => $resourceType,
            'invalidate' => true,
        ]);
    }
}
