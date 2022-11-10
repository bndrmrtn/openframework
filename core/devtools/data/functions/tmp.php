<?php

function tmpfolder(){
    if(!is_dir(ROOT . '/.tmp')) mkdir(ROOT . '/.tmp');
    $dir = ROOT . '/.tmp/' . microtime(true);
    mkdir($dir);
    return $dir;
}