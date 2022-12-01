<?php

namespace DEV;

class Serve extends ClassROOT {

    public static $config = [
        'port' => 7000,
        'host' => 'localhost',
    ];
    private static $hoststore = CORE . '/cache/dev/host.php';

    public static function getStore(){
        return self::$hoststore;
    }

    public static function run($custom_ports = false){
        createPath(dirname(self::$hoststore));
        
        headerPrintBg( 'Starting development server',true);
        if(!$custom_ports){
            if(file_exists(self::$hoststore)){
                $saved_host_config = require self::$hoststore;
                if(isset($saved_host_config['host']) && $saved_host_config['port']){
                    if(choice("\n" . 'Do you want to use the previous settings? (' . $saved_host_config['host'] . ':' . $saved_host_config['port'] . ')')){
                        self::$config = $saved_host_config;
                    }
                }
            }
        }
        self::createports(self::$config['port']);
        $host = static::$config['host'] . ':' . static::$config['port'];
        // self::browser('http://' . $host);
        $host_data = [
            'host' => static::$config['host'],
            'port' => static::$config['port'],
        ];
        file_put_contents(self::$hoststore,'<?php return ' . var_export($host_data, true) . '; ?>');
        shell_exec('php -S ' . $host . ' -t ' . ROOT . '/public/' . ' ' . CORE . '/server.php');
    }

    public static function customPort($data){
        $config = self::mkprops($data,true);
        if(isset($config['port'])) self::$config['port'] = $config['port'];
        if(isset($config['host'])) self::$config['host'] = $config['host'];
        self::run(true);
    }

    private static function createports($port = 7000){
        $ping = self::ping(self::$config['host'],$port,10);
        if($ping == 'down'){
            _e( "\n" . 'Server url: http://' . self::$config['host'] . ':' . $port . "\n" );
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