<?php

class HTML {
    private static $servedJS = [];

    public static function bootstrap(){
        return '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
';
    }

    public static function style($url,$other_attr = ''){
        if($other_attr != ''){
            $other_attr = " $other_attr";
        }
        return '<link rel="stylesheet" href="' . $url . '"' . $other_attr . '>
';
    }

    public static function script($url,$other_attr = ''){
        if($other_attr != ''){
            $other_attr = " $other_attr";
        }
        return '<script src="' . $url . '"' . $other_attr . '></script>
';
    }

    public static function rlink(){
        return 'rlink';
    }

    public static function rjs(){
        return '<script src="'. SRCDIR .'/js/router.js" defer></script>' . "\n";
    }

    public static function createServerJS(string $path,callable $data){
        if(is_callable($data)){
            self::$servedJS[$path] = $data;
        }
    }

    public static function serveJS(string $path){
        if(isset(self::$servedJS[$path])){
            self::$servedJS[$path]();
        }
    }

}