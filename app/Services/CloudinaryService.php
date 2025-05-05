<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                    'secure' => config('cloudinary.secure'),
                ],
            ])
        );
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param UploadedFile|string $file File to upload (can be a path or an UploadedFile instance)
     * @param string|null $publicId Optional custom public ID
     * @return array Upload result with URL and other info
     */
    public function uploadImage($file, $publicId = null)
    {
        $uploadOptions = [
            'folder' => config('cloudinary.folder'),
            'overwrite' => true,
        ];

        if ($publicId) {
            $uploadOptions['public_id'] = $publicId;
        }

        // Handle both UploadedFile instances and file paths
        $filePath = ($file instanceof UploadedFile) ? $file->getRealPath() : $file;

        // Upload the file to Cloudinary
        $result = $this->cloudinary->uploadApi()->upload(
            $filePath,
            $uploadOptions
        );

        return [
            'public_id' => $result['public_id'],
            'url' => $result['secure_url'],
            'format' => $result['format'],
            'width' => $result['width'],
            'height' => $result['height'],
            'bytes' => $result['bytes'],
        ];
    }

    /**
     * Delete an image from Cloudinary.
     *
     * @param string $publicId Public ID of the image to delete
     * @return array Deletion result
     */
    public function deleteImage($publicId)
    {
        return $this->cloudinary->uploadApi()->destroy($publicId);
    }

    /**
     * Get an image URL from Cloudinary.
     * 
     * @param string $publicId Public ID of the image
     * @param array $options Transformation options
     * @return string Image URL
     */
    public function getImageUrl($publicId, array $options = [])
    {
        return $this->cloudinary->image($publicId)
            ->toUrl($options);
    }
} 