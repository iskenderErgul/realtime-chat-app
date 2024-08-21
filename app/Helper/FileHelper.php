<?php

namespace App\Helper;


use Illuminate\Http\UploadedFile;

class FileHelper
{

    public static function determineFileType(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();

        if (strpos($mimeType, 'image/') === 0 || in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return 'image';
        } elseif (strpos($mimeType, 'audio/') === 0 || in_array($extension, ['mp3', 'wav'])) {
            return 'audio';
        } elseif (strpos($mimeType, 'video/') === 0 || in_array($extension, ['mp4', 'mov'])) {
            return 'video';
        }

        return 'text';
    }
}
