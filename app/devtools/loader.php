<?php

use App\APP;

$type = APP::getMainAction();

switch($type){
    case 'serve':
        APP::serve();
    break;
    default:
        echo 'Unknow command';
    break;
}