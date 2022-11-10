<?php

function import(string $file,array $data = [],?int $code = 200){
     $import = startStrSlash($file);
     $debug = debug_backtrace();
     $call_dir = dirname($debug[0]['file']);
     $view_dir = Core\Cache\View::$store_dir;
     if(str_starts_with($call_dir, $view_dir)) $import = substr($call_dir, strlen($view_dir)) . $import;
     return view($import, $data, $code);
}