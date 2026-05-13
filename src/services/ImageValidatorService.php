<?php
namespace App\Services;

class ImageValidatorService
{
    public static array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    public static int $maxFileSize = 2097152; // 2 MB

    /**
     * Valida una imagen subida
     * @param string $fileTmpPath
     * @param string $fileName
     * @param int $fileSize
     * @return string|null Mensaje de error o null si es válida
     */
    public static function validateImage(string $fileTmpPath, string $fileName, int $fileSize): ?string
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($ext, self::$allowedExtensions, true)) {
            return 'Formato inválido. Solo se permiten JPG, PNG o WEBP.';
        }
        if ($fileSize > self::$maxFileSize) {
            return 'La imagen no puede superar los 2 MB.';
        }
        if (@getimagesize($fileTmpPath) === false) {
            return 'El archivo seleccionado no es una imagen válida.';
        }
        return null;
    }
}
