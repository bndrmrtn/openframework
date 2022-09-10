<?php

function urlOrAsset($asset){
    if(!filter_var($asset, FILTER_VALIDATE_URL)){
        return asset($asset);
    }
    return $asset;
}

function stylesheet($style,$attrs = ''){
    return '<link rel="stylesheet" href="'. urlOrAsset($style) . '"' . $attrs . '>' . "\n";
}

function script($script,$attrs = ''){
    return '<script src="'. urlOrAsset($script) . '"' . $attrs . '></script>' . "\n";
}