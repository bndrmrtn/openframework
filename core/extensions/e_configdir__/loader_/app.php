<?php

const e_configdir__ = CORE . '/extensions/e_configdir__';

$dirs = glob(CORE . '/extensions/*' , GLOB_ONLYDIR);

foreach($dirs as $dir){
    if($dir != e_configdir__){
        $ipath = $dir . '/manager.php';
        $ename = explode('/',$dir);
        $ename = $ename[array_key_last($ename)];
        if(file_exists($ipath)){
            if(!isset($GLOBALS['e_req_enabled'])){
                include_once $ipath;
            } else if(in_array($ename,$GLOBALS['e_req_enabled'])){
                include_once $ipath;
            }
        } 
    }
}