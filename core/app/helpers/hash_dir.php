<?php

function hashDirectory($directory){
     if (! is_dir($directory)){
          return false;
     }
 
    $files = array();
    $dir = dir($directory);
 
     while (false !== ($file = $dir->read())){
          if ($file != '.' and $file != '..'){
               if(!in_array($file, ['vendor', '.git', 'cache'])){
                    if (is_dir($directory . '/' . $file)){
                         $files[] = hashDirectory($directory . '/' . $file);
                    }
                    else {
                         $files[] = md5_file($directory . '/' . $file);
                    }
               }
          }
     }
 
    $dir->close();
 
    return md5(implode('', $files));
}