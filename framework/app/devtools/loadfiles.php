<?php

$tools = array(
    '/app.php',
    '/components/index.php'
);

foreach($tools as $tool){
    if(file_exists(__DIR__ . $tool)) include_once __DIR__ . $tool;
}