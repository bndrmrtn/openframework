<?php

namespace Console\Application;

use Core\Framework\Console\Color\Color;
use Core\Framework\Console\Color\ForegroundColor;
use Console;

class Main {

     public static function main() {
          $app = new self();
          $app->welcome();
     }

     public function welcome() {
          $created = [];
          $text = str_split("Welcome to the Console Application! Build something beautiful ;)");
          foreach($text as $char) {
               $created[] = Color::Foreground($char, ForegroundColor::random());
               echo implode('', $created) . "\r";
               usleep(50000);
          }
          Console\Log("\nFor more information visit: https://open.mrtn.vip/docs/console/");
     }

}