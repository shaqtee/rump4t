<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CompressUploadedImages
{
    public function handle($request, Closure $next)
    {
        foreach ($request->files->all() as $key => $file) {
            if (!($file instanceof UploadedFile)) {
                continue;
            }
            
            if ($file && $file->isValid() && Str::startsWith($file->getMimeType(), 'image/')) {
                // Open the image with GD
                $image = imagecreatefromstring(file_get_contents($file));

                if ($image === false) {
                    // If GD fails to load the image, skip compression
                    continue;
                }

                // Set the quality (0 - 100)
                $quality = 75;

                // Get the file extension
                $extension = strtolower($file->getClientOriginalExtension());

                // Generate a temporary file
                $tempPath = tempnam(sys_get_temp_dir(), 'compressed_');
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($image, $tempPath, $quality);
                        break;
                    case 'png':
                        // PNG is lossless, you can't change quality the same way
                        imagepng($image, $tempPath, round($quality / 10));
                        break;
                    case 'gif':
                        imagegif($image, $tempPath);
                        break;
                    default:
                        continue 2;
                }

                // Free up memory
                imagedestroy($image);

                // Replace the uploaded file in the request
                $request->files->set($key, new \Illuminate\Http\UploadedFile(
                    $tempPath,
                    $file->getClientOriginalName(),
                    $file->getMimeType(),
                    null,
                    true // mark as test (temporary) file
                ));
            }
        }

        return $next($request);
    }
}
