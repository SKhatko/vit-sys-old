<?php namespace App;

class ImageResizer
{

    public static function createImage($fileName, $imagePath, $storeDirectory, $storePath, $width, $height)
    {

        list($originalW, $originalH, $type) = getimagesize($imagePath);

        $jpeg_quality = 100;

        //check if directory exists
        if (!file_exists($storeDirectory)) {
            mkdir($storeDirectory, 0755, true);
        }
        $output_filename = str_replace('.', '_thumb.', $fileName);

        if (($originalW / $originalH) >= ($width / $height)) {
            $newH = $height;
            $newW = intval(($newH / $originalH) * $originalW);
        } else {
            $newW = $width;
            $newH = intval(($newW / $originalW) * $originalH);
        }

        $dest_x = intval(($width - $newW) / 2);
        $dest_y = intval(($height - $newH) / 2);

        if ($type === 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        } else if ($type === 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        } else if ($type === 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        } else {
            return false;
        }

        $oldImage = $imgcreatefrom($imagePath);
        $newImage = imagecreatetruecolor($width, $height);

        imagecopyresampled($newImage, $oldImage, $dest_x, $dest_y, 0, 0, $newW, $newH, $originalW, $originalH);
        $imgt($newImage, $storePath);
        return file_exists($storePath);

    }
}