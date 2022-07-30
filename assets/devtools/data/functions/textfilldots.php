<?php

function textFillDots($data){

    $find = ':dots:';

    $cols = intval(exec('tput cols'));

    $data = explode("\n",$data);

    foreach($data as $txt){
        $textlen = strlen($txt) - strlen($find);
        $dots = $cols - $textlen;
        if($dots > 0) _e( str_replace($find,str_repeat('.',$dots),$txt) );
        else _e( str_replace($find,'.',$txt) );
    }

}