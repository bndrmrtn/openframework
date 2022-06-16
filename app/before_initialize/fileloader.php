<?php

function scanDirectory($dir){
    return array_diff(scandir($dir), array('..', '.'));
}