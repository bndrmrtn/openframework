<?php

function getStream($stream){
    if(!str_contains($stream,'*custom:')) return $stream;
    $data = explode('*custom:',$stream);
    return $data[array_key_last($data)];
}