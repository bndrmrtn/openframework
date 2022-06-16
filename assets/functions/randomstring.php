<?php

function randomString($n,$uppercase = true,$lowercase = true,$numbers = true) {
    $characters = '';
    if($uppercase){
        $characters = $characters.'QWERTZUIOPASDFGHJKLYXCVBNM';
    }
    if($lowercase){
        $characters = $characters.'qwertzuiopasdfghjklyxcvbnm';
    }
    if($numbers){
        $characters = $characters.'0123456789';
    }
    $str = '';
    for($i = 0; $i < $n; $i++){
        $index = rand(0, strlen($characters) -1);
        $str .= $characters[$index];
    }
    return $str;
}