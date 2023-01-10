<?php

function textFillDots($data){

    $find = ':dots:';

    
    if(PHP_OS !== 'WINNT') $cols = intval(exec('tput cols'));
    else $cols = 0;

    if(!is_int($cols)) $cols = 0;

    $data = explode("\n",$data);

    foreach($data as $txt){
        $textlen = strlen($txt) - strlen($find);
        $dots = $cols - $textlen;
        if($dots > 0) _e( str_replace($find,str_repeat('.',$dots),$txt) );
        else _e( str_replace($find,'.',$txt) );
    }

}

function terminalCenter($text, $pad_string = ' ') {
    if(PHP_OS !== 'WINNT') $window_size = (int) intval(exec('tput cols'));
    else $window_size = 0;
    return str_pad($text, $window_size, $pad_string, STR_PAD_BOTH)."\n";
}