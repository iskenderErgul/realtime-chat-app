<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class MessageTypeHelper
{
    public static function getMessageType($messageContent, $file = null)
    {
        if ($file instanceof UploadedFile) {

            $extension = $file->getClientOriginalExtension();
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                return 'image';
            } elseif (in_array($extension, ['mp3', 'wav'])) {
                return 'audio';
            } elseif (in_array($extension, ['mp4', 'avi', 'mov'])) {
                return 'video';
            } else {
                return 'file'; // Diğer dosya türleri
            }
        } elseif (!empty($messageContent)) {
            return 'text'; // Metin mesajı
        }

        return 'unknown'; // Tanımlanamayan tür
    }

    public static function determineMessageType($messageContent, $file = null)
    {
        return self::getMessageType($messageContent, $file);
    }
}
