<?php

const e_configdir__ = ROOT . '/extensions/e_configdir__';

$dirs = glob(ROOT . '/extensions/*' , GLOB_ONLYDIR);

foreach($dirs as $dir){
    if($dir != e_configdir__){
        $ipath = $dir . '/manager.php';
        if(file_exists($ipath)) include_once $ipath;
    }
}