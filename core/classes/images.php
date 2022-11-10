<?php

namespace Core\App\Storage;

class Image {

    protected $imginfo = [];
    private $binary = '';

    public function __construct($binary){
        try {
            $size = getimagesizefromstring($binary);
        } catch(Exception $e){
            dd($e);
        }
        $this->imginfo['size'] = $size;
        $this->imginfo['ext'] = $size['mime'];
        $this->binary = $binary;
    }

    public function resize($w,$h,$q = 100){
        $imgsize = $this->imginfo['size'];
        $source = imagecreatefromstring($this->binary);
        $destination = imagecreatetruecolor($w, $h);
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $w, $h, $imgsize[0], $imgsize[1]);
        ob_start();
        imagejpeg($destination, null, $q);
        $binary = ob_get_contents();
        ob_clean();
        $this->binary = $binary;
        $this->imginfo['size'] = [
            0 => $w,
            1 => $h,
        ];
    }

    public function get_size(){
        return $this->imginfo['size'];
    }

    public function get_ext(){
        return $this->imginfo['ext'];
    }

    public function binary($quality = NULL){
        if($quality != NULL){
            return $this->quality($quality);
        } else {
            return $this->binary;
        }
    }

    public function quality($q){
        $img = imagecreatefromstring($this->binary);
        ob_start();
        imagejpeg($img,null,$q);
        $binary = ob_get_contents();
        ob_clean();
        imagedestroy($img);
        return $binary;
    }

    public function render($quality = 100){
        $img = imagecreatefromstring($this->binary);
        header('Content-Type: ' . $this->imginfo['ext']);
        imagejpeg($img,null,$quality);
        imagedestroy($img);
    }

    public function reduce_ratio($maxwidth){
        $width = $this->imginfo['size'][0];
        $height = $this->imginfo['size'][1];

        if($width > $maxwidth || $height > $maxwidth){
            $i = $width / $maxwidth;
            $height = round($height / $i);
            $width = $maxwidth;
        }
        if($height > $maxwidth){
            $i = $height / $maxwidth;
            $width = round($width / $i);
            $height = $maxwidth;
        }
        $this->resize($width,$height);
    }

}