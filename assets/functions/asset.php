<?php

function asset($asset){
    if(str_starts_with($asset,'/')){
        $asset = substr($asset,1);
    }
    return BASE_URL . '/' . $asset;
}