<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload and process product image
     *
     * @param UploadedFile $file
     * @param string $productSlug
     * @return string Path to stored image
     */
    public function uploadProductImage(UploadedFile $file, string $productSlug): string
    {
        $filename = $productSlug . '-' . time() . '.' . $file->extension();
        $path = 'products/' . $filename;

        // Read and resize the image
        $image = $this->imageManager->read($file->getRealPath());

        // Resize to max width/height while maintaining aspect ratio
        $image->scale(width: 1200, height: 1600);

        // Encode to jpg with compression
        $encoded = $image->toJpeg(quality: 85);

        // Store the image
        Storage::disk('public')->put($path, $encoded);

        return $path;
    }

    /**
     * Delete product image
     *
     * @param string $path
     * @return bool
     */
    public function deleteProductImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Get full URL for image path
     *
     * @param string $path
     * @return string
     */
    public function getImageUrl(string $path): string
    {
        return asset('storage/' . $path);
    }
}
