<?php

/** updated with winpath function */
function import(string $file,array $data = [],?int $code = 200){
     $import = startStrSlash($file);
     $debug = debug_backtrace();
     $call_dir = winpath(dirname($debug[0]['file']));
     $view_dir = winpath(Core\Cache\View::$store_dir);
     if(str_starts_with($call_dir, $view_dir)) $import = substr($call_dir, strlen($view_dir)+1) . $import;
     $app_token = require core('applock.token.php');
     $app_token = $app_token['framework_builtin_views_directory'] ?: '<empty>';
     if(str_starts_with($import, $app_token)) $import = str_replace($app_token, '.src/:', $import);
     return view($import, $data, $code);
}