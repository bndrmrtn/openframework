<?php

function headerPrint($text = ''){
    echo(DEV\ColorCLI::getColoredString(terminalCenter($text),'red'));
}

function headerPrintBg($text = '',$p = false){
    if($p) headerPrintBg(str_repeat(' ',exec('tput cols')));
    echo(DEV\ColorCLI::getColoredString(terminalCenter($text),NULL,'red'));
    if($p) headerPrintBg(str_repeat(' ',exec('tput cols')));
}