<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

class ImageTrait
{
    /**
     * Get the Ip Address of the user.
     *
     * @return string
     */
    public function uploadIMage($image, $dimensions, $name, $folder = 'other', $rotate = true)
    {
        $fit = explode(',', $dimensions);
        $path = 'uploads/' . $folder;
        if (!file_exists($path))
            mkdir($path, 0777, true);

        $width = Image::make($image)->width();
        $height = Image::make($image)->height();

        if ($rotate)
            if ($width > $height) {
                $image = Image::make($image)->rotate(-90);
            }

        $front = Image::make($image)->fit($fit[0], $fit[1])->save($path . '/' . $name . '.webp');

        return $front->basePath();
    }

    public function uploadImage1($image, $dimensions, $name, $folder = 'other')
    {
        $resize = explode(',', $dimensions);
        $path = 'uploads/' . $folder;

        // Hakikisha folder lipo
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // Tengeneza instance ya Image
        $img = Image::make($image);

        // Rotate ikiwa inatakiwa na orientation ni landscape
        // if ($rotate) {
        //     if ($img->width() > $img->height()) {
        //         $img->rotate(-90);
        //     }
        // }

        // Punguza size ya picha bila kukata (keep ratio)
        $img->resize($resize[0], $resize[1], function ($constraint) {
            $constraint->aspectRatio();  // hifadhi ratio
            $constraint->upsize();       // usiongeze picha ndogo
        });

        // Pata extension ya original (e.g., jpg, png)
        $extension = $image->getClientOriginalExtension();
        $filename = $name . '.' . $extension;

        // Hifadhi picha kwa ubora mzuri (90%)
        $img->save($path . '/' . $filename, 90);

        return $path . '/' . $filename;
    }
}
