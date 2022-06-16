<?php

function _makeenv(array $data){
    eglob('env',$data);
}

function _env($type, $else = NULL){
    $_ENV = eglob('env');
    if(isset($_ENV[$type])){
        return $_ENV[$type];
    }
    return $else;
}

require ROOT . '/.env.php';