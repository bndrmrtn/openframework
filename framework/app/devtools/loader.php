<?php

use App\APP;
use App\AppSetMode;

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
    case 'mode:api':
        require __DIR__ . '/setmode/api.php';
        AppSetMode::makeAPI();
    break;
    case 'mode:auth-api':
        require __DIR__ . '/setmode/api.php';
        AppSetMode::downloadAuth();
    break;
    default:
        echo 'Unknow command';
    break;
}