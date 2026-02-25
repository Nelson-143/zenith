<?php

namespace app\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    public function __construct()
    {
        // Initialize Cloudinary configuration using .env values
        Configuration::instance(env('CLOUDINARY_URL'));
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param string $filePath Path to the image file.
     * @param string $folder Folder where the image will be stored on Cloudinary.
     * @return string URL of the uploaded image.
     */
    public function uploadImage($filePath, $folder = 'default_folder')
    {
        $response = (new UploadApi())->upload($filePath, ['folder' => $folder]);
        return $response['secure_url'];
    }

    /**
     * Apply transformations to an uploaded image.
     *
     * @param string $publicId Public ID of the image.
     * @param array $transformation Transformations to apply.
     * @return string URL of the transformed image.
     */
    public function transformImage($publicId, $transformation = [])
    {
        return cloudinary_url($publicId, $transformation);
    }
}
