<?php
// CloudinaryService.php

require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    protected static function cloudinary(): Cloudinary
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => getenv('CLOUDINARY_API_KEY'),
                'api_secret' => getenv('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    /**
     * @param string $filePath path to uploaded file on disk
     * @return array           the full upload result array (incl. secure_url)
     */
    public static function upload(string $filePath): array
    {
        $uploadApi = new UploadApi(self::cloudinary()->config);
        return $uploadApi->upload($filePath);
    }
}
