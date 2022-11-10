<?php

function dd($var){
    echo "\n-----dd output start-----\n\n";
    var_dump($var);
    echo "\n-----dd output end-----\n";
    exit;
}