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
    case 'update':
        require __DIR__ . '/updater/run.php';
    break;
    default:
        echo 'Unknow command';
    break;
}