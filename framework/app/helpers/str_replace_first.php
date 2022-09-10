<?php

function str_replace_first($search, $replace, $subject){
    $search = '/'.preg_quote($search, '/').'/';
    return preg_replace($search, $replace, $subject, 1);
}