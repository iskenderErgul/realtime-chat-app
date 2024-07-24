<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{

    public static function upload($file)
    {
        $filePath = $file->store('messages', 'public');
        return $filePath;
    }
}
