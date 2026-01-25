<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadFile
{

    /**
     * @param UploadedFile $file
     * @param string $path - путь куда записать файл
     * @param int $maxWidth - максимальная ширина
     * @param int $maxHeight- максимальная высота
     * @return bool
     */
    function upload(UploadedFile $file, string $path, int $maxWidth = 0, int $maxHeight = 0)
    {
        if(!$maxWidth && !$maxHeight){
            return Storage::disk('public')->put($path, $file);
        }

        $imageFile =  Storage::disk('public')->put($path, $file);
        $storageImageFile = Storage::disk('public')->path($imageFile);

        $img = Image::make($storageImageFile)->resize($maxWidth, $maxHeight, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($storageImageFile);

        $img->save($storageImageFile);

        return $imageFile;
    }
}
