<?php

function mapDir($dir){
    if(is_dir($dir)){
        $files = scanDirectory($dir);
        if(!empty($files)) foreach($files as $file){
            return endStrSlash($dir) . $file;
        }
    }
    return [];
}