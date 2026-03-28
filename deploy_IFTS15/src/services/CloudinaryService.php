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
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
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
     * Elimina una imagen de Cloudinary por public_id
     * @param string $publicId
     * @return array
     */
    public function deleteImage($publicId)
    {
        return $this->cloudinary->uploadApi()->destroy($publicId);
    }
}
