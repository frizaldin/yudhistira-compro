<?php

namespace App\Http\Controllers;

use InvalidArgumentException;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

abstract class Controller
{
    public function upload($img, $folder)
    {
        try {
            $folder_ext = explode('/', $folder);
            $basePath = rtrim(env('BASE_PATH_IMG', base_path('public')), '/\\') . '/';
            $storagePath = $basePath . 'storage/upload/' . $folder;

            if (! file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $extension = strtolower($img->getClientOriginalExtension());
            $filename = rand(100000, 999999) . '_' . $folder_ext[0];

            $mimeType = $img->getMimeType();
            $isImage = str_starts_with($mimeType, 'image/');

            if ($isImage) {
                $webpPath = $storagePath . '/' . $filename . '.webp';

                $tempFileName = $filename . '_temp.' . $extension;
                $tempFilePath = $storagePath . '/' . $tempFileName;

                $img->move($storagePath, $tempFileName);

                if (!file_exists($tempFilePath)) {
                    throw new InvalidArgumentException('Failed to save temporary file', 500);
                }

                $manager = new ImageManager(new Driver());
                $fileContent = file_get_contents($tempFilePath);
                $image = $manager->read($fileContent);
                $image->toWebp(90)->save($webpPath);

                if (file_exists($tempFilePath)) {
                    unlink($tempFilePath);
                }

                return 'storage/upload/' . $folder . '/' . $filename . '.webp';
            }

            $originalFileName = $filename . '.' . $extension;
            $img->move($storagePath, $originalFileName);

            return 'storage/upload/' . $folder . '/' . $originalFileName;
        } catch (\Throwable $th) {
            throw new InvalidArgumentException($th->getMessage(), 500);
        }
    }

    public function uploadNormal($img, $folder)
    {
        try {
            // Validate file size (maximum 2MB)
            $maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if ($img->getSize() > $maxSize) {
                throw new InvalidArgumentException('File size exceeds maximum limit of 2MB', 400);
            }

            $path = 'storage/upload/' . $folder;
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $extension = $img->getClientOriginalExtension();
            $originalName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);

            // Replace spaces with dash and sanitize filename
            $filename = str_replace(' ', '-', $originalName);
            // Remove special characters except dash, underscore, and alphanumeric
            $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $filename);
            // Remove multiple consecutive dashes
            $filename = preg_replace('/-+/', '-', $filename);
            // Trim dashes from start and end
            $filename = trim($filename, '-');

            // If filename is empty after sanitization, use a default name
            if (empty($filename)) {
                $filename = 'file';
            }

            // Check if file already exists, add timestamp if needed
            $file = $filename . '.' . $extension;
            $fullPath = $path . '/' . $file;
            if (file_exists($fullPath)) {
                $file = $filename . '_' . time() . '.' . $extension;
            }

            $img->move($path, $file);
            return 'storage/upload/' . $folder . '/' . $file;
        } catch (\Throwable $th) {
            throw new InvalidArgumentException($th->getMessage(), 500);
        }
    }

    /**
     * Upload file video (mp4, mkv, webm, dll). Max 200MB.
     */
    public function uploadVideo($file, $folder)
    {
        $maxSize = 200 * 1024 * 1024; // 200MB
        if ($file->getSize() > $maxSize) {
            throw new InvalidArgumentException('Ukuran file video maksimal 200MB', 400);
        }
        $path = 'storage/upload/' . $folder;
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = 'video_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        $file->move($path, $filename);
        return 'storage/upload/' . $folder . '/' . $filename;
    }

    function deleteFile($img)
    {
        if (file_exists($img)) {
            unlink($img);
        }
    }
}
