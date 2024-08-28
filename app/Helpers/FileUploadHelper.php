<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    public static function upload(UploadedFile $file, $directory = 'uploads')
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = $file->storeAs($directory, $fileName, 'public');
        return $filePath;
    }
}

