<?php

$_dbfiles = [
    '/config.php',
    '/assets/db.php',
    '/assets/sql.php',
    '/assets/table.php',
];

foreach($_dbfiles as $file){
    require __DIR__.$file;
}

DB::createConnection($dbconfig);