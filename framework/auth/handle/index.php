<?php

RR::addPath('api','api/{handle}');

RR::addPath('html','/{handle}');

$props = RR::getProps(true);

if($props['name'] == 'api'){
    ;
}

$file = __DIR__ . '/' . $props['name'] . '/' . regex::escape($props['props']['handle']) . '.php';

if(file_exists($file)){
    require $file;
} else {
    MErrors::NotFound();
}