<?php

namespace Core\Framework\Console\Color;

use ReflectionClass;

enum ForegroundColor {
     case DEFAULT;
     case BLACK;
     case BLUE;
     case GREEN;
     case CYAN;
     case RED;
     case PURPLE;
     case BROWN;
     case LIGHT_GRAY;
     case DARK_GRAY;
     case LIGHT_BLUE;
     case LIGHT_GREEN;
     case LIGHT_CYAN;
     case LIGHT_RED;
     case LIGHT_PURPLE;
     case YELLOW;
     case WHITE;
     case ORANGE;
     case LIGHT_ORANGE;
    
     public function color():string {
          return match($this) {
               ForegroundColor::DEFAULT => '39',
               ForegroundColor::BLACK => '30',
               ForegroundColor::BLUE => '34',
               ForegroundColor::GREEN => '32',
               ForegroundColor::CYAN => '36',
               ForegroundColor::RED => '31',
               ForegroundColor::PURPLE => '35',
               ForegroundColor::BROWN => '33',
               ForegroundColor::LIGHT_GRAY => '30',
               ForegroundColor::DARK_GRAY => '30',
               ForegroundColor::LIGHT_BLUE => '34',
               ForegroundColor::LIGHT_GREEN => '32',
               ForegroundColor::LIGHT_CYAN => '36',
               ForegroundColor::LIGHT_RED => '31',
               ForegroundColor::LIGHT_PURPLE => '35',
               ForegroundColor::YELLOW => '33',
               ForegroundColor::WHITE => '37',
               ForegroundColor::ORANGE => '38;5;202',
               ForegroundColor::LIGHT_ORANGE => '38;5;208',
          };
     }

     public static function random(){
          $ref = new ReflectionClass(self::class);
          $colors = $ref->getConstants();
          return $colors[array_rand($colors)];
     }
}

enum BackgroundColor {
     case DEFAULT;
     case BLACK;
     case BLUE;
     case GREEN;
     case CYAN;
     case RED;
     case MAGENTA;
     case LIGHT_MAGENTA;
     case LIGHT_GRAY;
     case DARK_GRAY;
     case LIGHT_BLUE;
     case LIGHT_GREEN;
     case LIGHT_CYAN;
     case LIGHT_RED;
     case YELLOW;
     case WHITE;
     case ORANGE;
     case LIGHT_ORANGE;
    
     public function color():string {
          return match($this) {
               BackgroundColor::DEFAULT => '49',
               BackgroundColor::BLACK => '40',
               BackgroundColor::BLUE => '44',
               BackgroundColor::GREEN => '42',
               BackgroundColor::CYAN => '46',
               BackgroundColor::RED => '41',
               BackgroundColor::MAGENTA => '45',
               BackgroundColor::LIGHT_MAGENTA => '105',
               BackgroundColor::LIGHT_GRAY => '47',
               BackgroundColor::DARK_GRAY => '100',
               BackgroundColor::LIGHT_BLUE => '104',
               BackgroundColor::LIGHT_GREEN => '102',
               BackgroundColor::LIGHT_CYAN => '106',
               BackgroundColor::LIGHT_RED => '101',
               BackgroundColor::YELLOW => '43',
               BackgroundColor::WHITE => '107',
               BackgroundColor::ORANGE => '202',
               BackgroundColor::LIGHT_ORANGE => '208',
          };
     }

     public static function random(){
          $ref = new ReflectionClass(self::class);
          $colors = $ref->getConstants();
          return $colors[array_rand($colors)];
     }
}