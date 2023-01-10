<?php

function headerPrint($text = ''){
    echo(DEV\ColorCLI::getColoredString(terminalCenter($text),'red'));
}

function headerPrintBg($text = '',$p = false){
    if(PHP_OS !== 'WINNT') $cols = intval(exec('tput cols'));
    else $cols = 0;
    if(!is_int($cols)) $cols = 5;
    if($p) headerPrintBg(str_repeat(' ',$cols));
    echo(DEV\ColorCLI::getColoredString(terminalCenter($text),NULL,'red'));
    if($p) headerPrintBg(str_repeat(' ',$cols));
}