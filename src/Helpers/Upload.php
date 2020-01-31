<?php

namespace Bitfumes\ApiAuth\Helpers;

use Intervention\Image\ImageManagerStatic;

class Upload
{
    public static function resize($image, $width, $height=null)
    {
        $image    = ImageManagerStatic::make($image)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
        return $image;
    }
}
