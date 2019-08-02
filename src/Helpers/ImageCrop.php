<?php

namespace Bitfumes\ApiAuth\Helpers;

class ImageCrop
{
    public static function crop($im, $width, $height)
    {
        $source_width              = imagesx($im);
        $source_height             = imagesy($im);

        $thumb        = imagecreatetruecolor($width, $height);
        $transparency = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
        imagefilledrectangle($thumb, 0, 0, $width, $height, $transparency);
        imagecopyresampled($thumb, $im, 0, 0, 0, 0, $width, $height, $source_width, $source_height);
        imagepng($thumb, storage_path('temp.jpg'));
        return file_get_contents(storage_path('temp.jpg'));
    }

    public static function clearnUp($im)
    {
        unlink(storage_path('temp.jpg'));
        imagedestroy($im);
    }
}
