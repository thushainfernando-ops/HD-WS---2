<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Illuminate\Support\Facades\Log;

/**
 * Cloudinary Image Service
 * 
 * Handles image management for Hot Dish
 * - Upload menu item images
 * - Optimize and resize images
 * - Store image URLs in database
 * - Manage image transformations
 */
class CloudinaryImageService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
        ]);
    }

    /**
     * Upload an image file to Cloudinary
     * 
     * @param string|\Illuminate\Http\UploadedFile $file File path or UploadedFile
     * @param string $folder Folder name in Cloudinary
     * @param string $publicId Optional public ID
     * @return array Upload response
     */
    public function uploadImage($file, $folder = 'hotdish', $publicId = null)
    {
        try {
            $uploadOptions = [
                'folder' => $folder,
                'resource_type' => 'auto',
                'quality' => 'auto',
            ];

            if ($publicId) {
                $uploadOptions['public_id'] = $publicId;
            }

            $response = $this->cloudinary->uploadApi()->upload($file, $uploadOptions);

            Log::info('Image Uploaded to Cloudinary', [
                'public_id' => $response['public_id'],
                'url' => $response['secure_url'],
                'size' => $response['bytes'],
            ]);

            return [
                'success' => true,
                'public_id' => $response['public_id'],
                'url' => $response['secure_url'],
                'width' => $response['width'],
                'height' => $response['height'],
                'size' => $response['bytes'],
                'format' => $response['format'],
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary Upload Failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get optimized image URL with transformations
     * 
     * @param string $publicId Image public ID
     * @param int $width Width in pixels
     * @param int $height Height in pixels
     * @param string $crop Crop method
     * @return string Transformed image URL
     */
    public function getOptimizedUrl($publicId, $width = 400, $height = 300, $crop = 'fill')
    {
        try {
            $url = $this->cloudinary->image($publicId)
                ->resize(Resize::fill($width, $height))
                ->quality('auto')
                ->toUrl();

            return $url;
        } catch (\Exception $e) {
            Log::error('Failed to Generate Cloudinary URL', [
                'error' => $e->getMessage(),
                'public_id' => $publicId,
            ]);

            return null;
        }
    }

    /**
     * Get thumbnail URL for menu items
     * 
     * @param string $publicId Image public ID
     * @return string Thumbnail URL
     */
    public function getThumbnailUrl($publicId)
    {
        return $this->getOptimizedUrl($publicId, 200, 200, 'thumb');
    }

    /**
     * Get banner/hero image URL
     * 
     * @param string $publicId Image public ID
     * @return string Banner URL
     */
    public function getBannerUrl($publicId)
    {
        return $this->getOptimizedUrl($publicId, 1200, 400, 'fill');
    }

    /**
     * Delete an image from Cloudinary
     * 
     * @param string $publicId Image public ID
     * @return array Deletion response
     */
    public function deleteImage($publicId)
    {
        try {
            $response = $this->cloudinary->uploadApi()->destroy($publicId);

            Log::info('Image Deleted from Cloudinary', [
                'public_id' => $publicId,
                'result' => $response['result'],
            ]);

            return [
                'success' => true,
                'message' => 'Image deleted successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary Deletion Failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get image metadata
     * 
     * @param string $publicId Image public ID
     * @return array Image metadata
     */
    public function getImageMetadata($publicId)
    {
        try {
            $response = $this->cloudinary->adminApi()->asset($publicId);

            return [
                'success' => true,
                'public_id' => $response['public_id'],
                'format' => $response['format'],
                'width' => $response['width'],
                'height' => $response['height'],
                'bytes' => $response['bytes'],
                'created_at' => $response['created_at'],
                'url' => $response['secure_url'],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to Get Cloudinary Metadata', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get multiple image URLs with optimization
     * 
     * @param array $publicIds Array of public IDs
     * @return array Array of optimized URLs
     */
    public function getMultipleOptimizedUrls($publicIds)
    {
        $urls = [];
        foreach ($publicIds as $publicId) {
            $urls[$publicId] = $this->getOptimizedUrl($publicId);
        }

        return $urls;
    }
}
