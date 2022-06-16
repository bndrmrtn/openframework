<?php

namespace App;

class APP {
    private static $args = [];

    public static function initialize($args){
        self::$args = $args;
    }
    public static function getMainAction(){
        return self::$args[1];
    }

    public static function serve($port = 7000){
        echo 'Starting development server...';
        if(isset(self::$args[2]) && is_numeric(self::$args[2])){
            $port = intval(self::$args[2]);
        }
        $ping = self::ping('localhost',$port,10);
        if($ping == 'down'){
            echo "\n";
            echo 'Server url: http://localhost:' . $port;
            echo "\n";
            self::startDevServer($port);
        } else {
            self::serve(substr($port,0,2) . rand(10,99));
        }
    }

    public static function ping($host, $port, $timeout) {
        $tB = microtime(true);
        $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
        if (!$fP) { return 'down'; }
        $tA = microtime(true);
        return round((($tA - $tB) * 1000), 0)." ms";
    }

    public static function startDevServer($port){
        $host = 'localhost:' . $port;
        self::open('http://' . $host);
        shell_exec('php -S ' . $host . ' -t '. ROOT . '/public/');
    }

    public static function  open(string $url): void{
        switch (PHP_OS) {
            case 'Darwin':
                $opener = 'open';
                break;
            case 'WINNT':
                $opener = 'start';
                break;
            default:
                $opener = 'xdg-open';
        }
        exec(sprintf('%s %s', $opener, $url));
    }


}