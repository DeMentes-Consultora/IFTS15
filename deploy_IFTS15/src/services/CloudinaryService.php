<?php
// src/services/CloudinaryService.php
namespace App\Services;

use Cloudinary\Cloudinary;
use Exception;

class CloudinaryService
{
    private $cloudinary;

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
    public function deleteImage($publicId)
    {
        return $this->cloudinary->uploadApi()->destroy($publicId);
    }
}
