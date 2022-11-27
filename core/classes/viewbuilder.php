<?php

namespace Core\Cache;

use Exception;
use Core\Base\Base;

class ViewBuilder extends Base {

     public static function extended($data, $file, $view){
          if(str_starts_with($data, '@extends:') && str_contains($data, ';')){
               $created = '';

               $data = explode(';', $data, 2);
               $extend = trim(str_replace('@extends:', '', $data[0]));
               $data = $data[1];

               $e_view_fdata = View::filedata($extend);

               $extended_view_file = file_get_contents($e_view_fdata['view_file']);
               if(str_contains($extended_view_file, '@section:') && str_contains($extended_view_file, ';')){
                    $sections = self::getsections($extended_view_file);
                    foreach($sections as $name){
                         $yield_content = self::getsectioncontent($name, $data);
                         if(is_null($yield_content)) throw new Exception('No section found with "' . $name . '" name');
                         $extended_view_file = str_replace('@section:' . $name . ';', $yield_content, $extended_view_file);
                    }
                    $data = $extended_view_file;
               }
          }
          // otherwise just return the view data
          return $data;
     }

     private static function getsections($view_data){
          $arr = View::parser($view_data,'@section:',';');
          $section_names = [];
  
          while(!empty($arr)){
               foreach($arr as $k => $data){
                    $section_names[] = $data;
                    $view_data = str_replace('@section:' . $data . ';', '', $view_data);
               }
               $arr = View::parser($view_data,'@section:',';');
          }
          return $section_names;
     }

     private static function getsectioncontent($name, $view_data){
          $yieldstart = '@yield:' . $name . ';';
          $yieldend = '@endyield:' . $name . ';';

          $arr = View::parser($view_data, $yieldstart, $yieldend);
          $section_content = NULL;
  
          while(!empty($arr)){
               foreach($arr as $k => $data){
                    return $data;
               }
          }
          return $section_content;
     }
     
}