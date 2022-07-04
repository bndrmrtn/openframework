<?php

function location($url){
    header('Location: ' . $url);
    exit;
}