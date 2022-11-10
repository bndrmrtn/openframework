<?php

use Core\App\Request;

function response(){
    return Request::response();
}

function request(){
    return new Request;
}