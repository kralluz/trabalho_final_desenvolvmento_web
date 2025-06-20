<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileUploadService
{
    /**
     * Upload an image file to local storage
     * 
     * @param UploadedFile $file
     * @param string $folder
     * @return array
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images'): array
    {
        try {
            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            
            // Store in public disk under specified folder
            $path = $file->storeAs($folder, $filename, 'public');
            
            if (!$path) {
                throw new \Exception('Failed to store file');
            }

            // Get file URL and metadata            $url = asset('storage/' . $path);
            $size = $file->getSize();
            $mime = $file->getMimeType();
            
            return [
                'url' => $url,
                'path' => $path,
                'filename' => $filename,
                'metadata' => [
                    'size' => $size,
                    'mime' => $mime,
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $extension
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an image file from local storage
     * 
     * @param string $path Relative path from storage/app/public
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        try {
            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            \Log::error('File deletion error: ' . $e->getMessage());
            throw $e;
        }
    }
}