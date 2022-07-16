<?php

namespace App;

class Updater {

    public function __construct()
    {
        $need_update = readline('Are you sure you want to update? [Y/n] ');
        getDirContents(ROOT,$folders);
        $dirs = [];
        foreach($folders as $path) {
            $dirs[] = str_replace(ROOT,'',$path);
        }
        if(strtoupper($need_update) == 'Y' || strtoupper($need_update) == 'YES'){
            require_once FRAMEWORK . '/app/classes/http.php';
            if(!defined('BASE_URL')){
                define('BASE_URL','http://localhost:7000');
            }
            $post = \HTTP::post(API,[
                'Authorization' => API_KEY
            ],json_encode([
                'dirs' => $dirs,
                'root' => ROOT,
            ]),30,true);
            if(isset($post['eval'])){
                ob_start();
                eval($post['eval']);
                $run = ob_get_contents();
                ob_get_clean();

                echo $run;
            } else if(isset($post['errors'])){
                foreach($post['errors'] as $key => $error){
                    echo "\nError:\n";
                    echo "{$key}. {$error}\n";
                }
            } else {
                echo 'Something went wrong';
            }
        } else {
            echo "\nAborted.";
        }
    }

}

function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}