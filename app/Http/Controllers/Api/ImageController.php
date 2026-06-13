<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Services\CloudinaryImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Image API Controller
 * 
 * Handles image uploads and management
 * - Upload menu item images to Cloudinary
 * - Get optimized image URLs
 * - Delete images
 */
class ImageController extends Controller
{
    protected $imageService;

    public function __construct(CloudinaryImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Upload image to Cloudinary
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'folder' => 'nullable|string',
            'public_id' => 'nullable|string',
        ]);

        $folder = $validated['folder'] ?? 'hotdish';

        $result = $this->imageService->uploadImage(
            $request->file('image'),
            $folder,
            $validated['public_id'] ?? null
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'public_id' => $result['public_id'],
            'url' => $result['url'],
            'width' => $result['width'],
            'height' => $result['height'],
        ]);
    }

    /**
     * Get optimized image URL
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptimizedUrl(Request $request)
    {
        $validated = $request->validate([
            'public_id' => 'required|string',
            'width' => 'nullable|integer|min:50',
            'height' => 'nullable|integer|min:50',
        ]);

        $url = $this->imageService->getOptimizedUrl(
            $validated['public_id'],
            $validated['width'] ?? 400,
            $validated['height'] ?? 300
        );

        if (!$url) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate URL',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'url' => $url,
        ]);
    }

    /**
     * Delete image from Cloudinary
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request)
    {
        $validated = $request->validate([
            'public_id' => 'required|string',
        ]);

        $result = $this->imageService->deleteImage($validated['public_id']);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully',
        ]);
    }

    /**
     * Get image metadata
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImageMetadata(Request $request)
    {
        $validated = $request->validate([
            'public_id' => 'required|string',
        ]);

        $result = $this->imageService->getImageMetadata($validated['public_id']);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'metadata' => $result,
        ]);
    }
}
