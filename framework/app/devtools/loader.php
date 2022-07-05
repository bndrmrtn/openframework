<?php

use App\APP;

$type = APP::getMainAction();

switch($type){
    case 'serve':
        APP::serve();
    break;
    case 'components':
        APP::components();
    break;
    case 'db:setup-tables':
        APP::DBSetupTables();
    break;
    default:
        echo 'Unknow command';
    break;
}