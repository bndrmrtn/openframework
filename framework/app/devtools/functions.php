<?php

function choice(string $question,string $reactions = '[Y/n]',array $true_choices_in_uppercase = ['YES','Y']) :bool {
    $choice = readline("{$question} {$reactions} ");
    return in_array(strtoupper($choice),$true_choices_in_uppercase);
}

function _e($msg){
    echo "$msg\n";
}

function tmpfolder(){
    if(!is_dir(ROOT . '/.tmp')) mkdir(ROOT . '/.tmp');
    $dir = ROOT . '/.tmp/' . microtime(true);
    mkdir($dir);
    return $dir;
}

function gclss($class):void {
    if(!is_array($class)) require FRAMEWORK . "/app/classes/{$class}.php";
    if(is_array($class)){
        foreach($class as $c){
            require FRAMEWORK . "/app/classes/{$c}.php";
        }
    }
    return;
}