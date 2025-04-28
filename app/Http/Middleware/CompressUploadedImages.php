<?php

namespace App\Http\Middleware;

use Closure;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CompressUploadedImages
{
    public function handle($request, Closure $next)
    {
        foreach ($request->files->all() as $key => $file) {
            if ($file && $file->isValid() && Str::startsWith($file->getMimeType(), 'image/')) {
                // Read the image
                $img = Image::make($file);

                // Compress it
                $quality = 75; // you can change this
                $compressedImage = (string) $img->encode(null, $quality);

                // Create a temporary file to replace the original upload
                $tempPath = tempnam(sys_get_temp_dir(), 'compressed_');
                file_put_contents($tempPath, $compressedImage);

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
