<?php
use Illuminate\Http\UploadedFile;

if (!function_exists('uploadImage')) {
    /**
     * Upload an image
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    function uploadImage(UploadedFile $file, string $path): string
    {
        $uniqueName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store file in storage
        return $file->storeAs($path, $uniqueName, 'public');
    }
}
