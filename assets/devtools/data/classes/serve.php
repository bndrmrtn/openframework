<?php

namespace DEV;

class Serve extends ClassROOT {

    private static $config = [
        'port' => 7000,
        'host' => 'localhost',
    ];

    public static function run(){
        _e( 'Starting development server...' );
        self::createports(self::$config['port']);
        $host = static::$config['host'] . ':' . static::$config['port'];
        self::browser('http://' . $host);
        shell_exec('php -S ' . $host . ' -t ' . ROOT . '/public/');
    }

    public static function customPort($data){
        $config = self::mkprops($data,true);
        if(isset($config['port'])) self::$config['port'] = $config['port'];
        if(isset($config['host'])) self::$config['host'] = $config['host'];
        self::run();
    }

    private static function createports($port = 7000){
        $ping = self::ping(self::$config['host'],$port,10);
        if($ping == 'down'){
            _e( "\n" . 'Server url: http://localhost:' . $port . "\n" );
            self::$config['port'] = $port;
            return true;
        }
        self::createports(substr($port,0,2) . rand(10,99));
    }

    private static function ping($host, $port, $timeout) {
        $tB = microtime(true);
        try {
            $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
        } catch(Exception $e){
            return 'down';
        }
        if (!$fP) { return 'down'; }
        $tA = microtime(true);
        return round((($tA - $tB) * 1000), 0)." ms";
    }

    public static function  browser(string $url): void{
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