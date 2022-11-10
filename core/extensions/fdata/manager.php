<?php

function getrtime(){
    return microtime(true) - START_TIME;
}

function memusage(){
    return memory_get_usage() - F_MEM_USAGE;
}