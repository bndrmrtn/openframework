<?php

function loadDirFiles($dir){
    if(is_dir($dir)){
        $files = scanDirectory($dir);
        if(!empty($files)) foreach($files as $file){
            require endStrSlash($dir) . $file;
        }
    }
}