<?php

function push_appd(&$arr, $data){
     $datakey = 'data.app';
     if(_env('USE_DATA.NAME')){
          $datakey = 'data.' . str_replace(' ','_', strtolower(transliterateString(_env('NAME'))));
     }
     
     if(!isset($arr[$datakey])) $arr[$datakey] = [];
     foreach($data as $key => $val){
          if(!key_exists($key, $arr[$datakey])){
               $arr[$datakey][$key] = $val;
          } else {
               if(is_int($key)){
                    $arr[$datakey][] = $val;
               } else {
                    $arr[$datakey][] = [ $key => $val ];
               }
          }
     }
}