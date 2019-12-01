<?php

namespace Components\Tof\Image;

/**
 * Класс для работы с изображениями
 */
class Image {

    /** Изменяет размер изображения $fin 
     * вмисывая его в рамку с размерами $neww и $newh
     * и сохраняяя в $fout с качеством $quality
     * 
     * @param type $fout выходной файл
     * @param type $fin входной файл
     * @param type $neww ширина рамки, в котору требуется вписать исходное изображдение
     * @param type $newh высота рамки, в котору требуется вписать исходное изображдение
     * @param type $quality качество сжатия [1..100]
     * @return boolean
     */
    public static function scale($fout, $fin, $neww, $newh, $quality) {
        // сохраняем в себя
        if ($fout === NULL)
            $fout = $fin;

        if (!file_exists($fin))
            return false;

        $info = getimagesize($fin); //  получение информации о изображении 
        $ext = $info[2]; // тип изображения 

        $im = self::image_create($ext, $fin);

        imagealphablending($im, true);
        imagesavealpha($im, true);
        $transparent = imagecolorallocatealpha($im, 255, 255, 255, 0);

        if (!$im)
            return false;

        $k1 = $neww / imagesx($im);
        $k2 = $newh / imagesy($im);
        $k = $k1 > $k2 ? $k2 : $k1;

        $w = intval(imagesx($im) * $k);
        $h = intval(imagesy($im) * $k);

        $im1 = imagecreatetruecolor($w, $h);

        imagealphablending($im1, false);
        imagesavealpha($im1, true);
        $transparent = imagecolorallocatealpha($im1, 255, 255, 255, 127);

        imagecopyresampled($im1, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));

        self::image_save($im1, $fout, $ext, $quality);

        imagedestroy($im);
        imagedestroy($im1);
        return true;
    }

    /**
     *  Выбирает способ открытия файла в зависимости от типа изображения
     * @param string $ext расширение файла
     * @param string $fin путь к файлу
     * @return image дескриптор изображения
     */
    protected static function image_create($ext, $fin) {
        switch ($ext) {
            case 1: { // GIF		
                    $im = imagecreatefromgif($fin); /* попытка открыть */
                    break;
                }
            case 2: { // JPG
                    $im = imagecreatefromjpeg($fin);
                    break;
                }
            case 3: { // PNG
                    $im = imagecreatefrompng($fin);
                    imageColorTransparent($im);
                    break;
                }
            case 6: { // BMP
                    $im = @imagecreatefromwbmp($fin);
                    break;
                }
            default: { /* если ничего не подошло */
                    $im = 0;
                }
        }
        return $im;
    }

    /**
     *  выбор способа закрытия файла в зависимости от типа изображения 
     * @param image $im1 дескриптор изображения
     * @param string $fout путь к выходному файлу
     * @param string $ext расширение файла
     * @param int $quality качество сжатия
     */
    protected static function image_save($im1, $fout, $ext, $quality) {
        switch ($ext) {
            case 1: { // GIF			
                    imagegif($im1, $fout, $quality); /* выводим изображение в браузер */
                    break;
                }
            case 2: { // JPG
                    imagejpeg($im1, $fout, $quality);
                    break;
                }
            case 3: { // PNG
                    imagepng($im1, $fout);
                    break;
                }
            case 6: { // BMP
                    imagewbmp($im1, $fout);
                    break;
                }
        }
    }

}
