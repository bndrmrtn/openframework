<?php

RR::addPath('html','/{handle}');

$props = RR::getProps(true);

$file = __DIR__ . '/' . $props['name'] . '/' . regex::escape($props['props']['handle']) . '.php';

if(file_exists($file)){
    require $file;
} else {
    MErrors::NotFound();
}