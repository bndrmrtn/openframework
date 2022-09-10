<?php

function _e($msg = '',$exitmsg = false){
    echo "{$msg}\n";
    if($exitmsg) exit;
}