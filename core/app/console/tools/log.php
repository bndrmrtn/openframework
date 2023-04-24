<?php

namespace Console {

     use Core\Framework\Console\Color\Color;
     use Core\Framework\Console\Color\ForegroundColor;

     function Log(...$vars) {
          _e(implode(' ', $vars));
     }

     function TypeLog(...$vars){
          $types = [
               'string' => ForegroundColor::BLUE,
               'integer' => ForegroundColor::LIGHT_ORANGE,
               'float' => ForegroundColor::LIGHT_ORANGE,
               'boolean' => ForegroundColor::LIGHT_ORANGE,
               'NULL' => ForegroundColor::ORANGE,
          ];
          $out = '';
          foreach($vars as $key => $log){
               $type = gettype($log);
               if(array_key_exists($type, $types)) {
                    $out .= Color::Foreground(var_export($log, true), $types[$type]);
               } else $out .= var_export($log, true);
               if(array_key_last($vars) !== $key){
                    $out .= ' ';
               }
          }
          echo $out . "\n";
     }

}