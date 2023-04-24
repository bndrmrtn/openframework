<?php

namespace Core\Framework;

use Console\Application\Main;
use Core\Base\AutoRunner;
use Core\Framework\Console\Color\Color;
use Core\Framework\Console\Color\ForegroundColor;

class Console {
     
     private static $loadTimes = 10;

     public function run(){
          require_once path(__DIR__. '/color-enum.php');
          require_once path(__DIR__. '/console-colors.php');

          $cols = exec('tput cols');
          if(!is_int($cols)) $cols = 5;
          echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
          headerPrint(str_repeat(' ',$cols));
          headerPrint('   ____                      _           _');
          headerPrint('  / ___|___  _ __  ___  ___ | | ___     / \   _ __  _ __');
          headerPrint(" | |   / _ \| '_ \/ __|/ _ \| |/ _ \   / _ \ | '_ \| '_ \\");
          headerPrint(" | |__| (_) | | | \__ \ (_) | |  __/  / ___ \| |_) | |_) |");
          headerPrint("  \____\___/|_| |_|___/\___/|_|\___| /_/   \_\ .__/| .__/ ");
          headerPrint("                                             |_|   |_|    ");
          headerPrint(str_repeat(' ',$cols));
          $this->loader("Initializing");
          _e();
          loadDirFiles(__DIR__ . '/tools');
          loadDirFiles(root('/Console'));

          Main::main();
     }

     private function loader($text = 'Loading'){
          $chars = mb_str_split('⢿⣻⣽⣾⣷⣯⣟⡿');
          $len = count($chars);
          $i = 0;
          while ($i < self::$loadTimes) {
               $i++;
               echo $text . ' ' . Color::Foreground($chars[$i % $len], ForegroundColor::LIGHT_GREEN) . "\r";
               usleep(100000);
          }
     }

}