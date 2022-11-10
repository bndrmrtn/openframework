<?php

$dbconfig['createconnection'] = _env('USE_DB',false);
$dbconfig['host'] = _env('DB_HOST','localhost');
$dbconfig['port'] = _env('DB_PORT',3306);
$dbconfig['user'] = _env('DB_USER','root');
$dbconfig['password'] = _env('DB_PASSWORD','');
$dbconfig['dbname'] = _env('DB_NAME','test');
