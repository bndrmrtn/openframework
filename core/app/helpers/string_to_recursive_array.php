<?php

function strings_to_recursive_array($strings, $sep = '\\'){
     $result = [];

     foreach ($strings as $string) {
          $parts = explode($sep, $string);
          
          $current = &$result;
          
          foreach ($parts as $part) {
               if (!isset($current[$part])) {
                    $current[$part] = [];
               }
          
               $current = &$current[$part];
          }
          
          $current = array_pop($parts);
     }
     
     return $result;
}