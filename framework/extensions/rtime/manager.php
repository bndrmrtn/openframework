<?php

function getrtime(){
    return microtime(true) - M_START_TIME;
}