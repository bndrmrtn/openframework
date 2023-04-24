<?php

namespace Core\Base;

use Core\App\Helpers\Dates;

abstract class AutoRunner extends Base {

     private static $all_config = [];
     private static $delay = 1000;
     private static string $cache_dir = CORE . '/cache/auto_runner/';
     private static string $cache_file = 'auto_runner.php';

     public function delay($ms) {
          static::$delay = $ms;
     }

     private static function getCachePath() {
          return self::$cache_dir. self::$cache_file;
     }

     public static function start() {
          createPath(self::$cache_dir);
          $cache_path = self::getCachePath();
          $config = [];
          if (file_exists($cache_path)) $config = require $cache_path;
          if(array_key_exists(static::class, $config)){
               $config = $config[static::class];
          }
          if(isset($config['delay'])) static::$delay = $config['delay'];
          if(isset($config['lastRunned'])){
               if(Dates::addTo(['ms' => static::$delay], $config['lastRunned']) <= Dates::now()){
                    $runner = new static();
                    $runner->run();
                    static::saveState();
               } else return;
          }
          $runner = new static();
          $runner->run();
          static::saveState();
     }

     private static function saveState() {
          $config = static::$all_config;
          $config[static::class]['lastRunned'] = Dates::now();
          $config[static::class]['delay'] = static::$delay;
          file_put_contents(self::getCachePath(), '<?php return '.var_export($config, true).';');
     }

     public function run():void {}

}