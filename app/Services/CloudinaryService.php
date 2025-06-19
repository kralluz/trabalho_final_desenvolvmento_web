<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

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
     * @param array $options Additional upload options
     * @return array Upload result with URL and other info
     */
    public function uploadImage($file, $publicId = null, array $options = [])
    {
        try {
            $uploadOptions = array_merge([
                'folder' => config('cloudinary.folder'),
                'overwrite' => true,
                'resource_type' => 'image',
            ], $options);

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
        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an image from Cloudinary.
     *
     * @param string $publicId Public ID of the image to delete
     * @return array Deletion result
     */
    public function deleteImage($publicId)
    {
        try {
            return $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => 'image'
            ]);
        } catch (\Exception $e) {
            Log::error('Cloudinary delete error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get an image URL from Cloudinary with optional transformations.
     * 
     * @param string $publicId Public ID of the image
     * @param array $transformations Transformation options
     * @return string Image URL
     */
    public function getImageUrl($publicId, array $transformations = [])
    {
        return $this->cloudinary->image($publicId)
            ->toUrl($transformations);
    }

    /**
     * Get details about an image from Cloudinary.
     * 
     * @param string $publicId Public ID of the image
     * @return array Image details
     */
    public function getImageInfo($publicId)
    {
        try {
            return $this->cloudinary->adminApi()->asset($publicId, [
                'resource_type' => 'image'
            ]);
        } catch (\Exception $e) {
            Log::error('Cloudinary get image info error: ' . $e->getMessage());
            throw $e;
        }
    }
    }
}