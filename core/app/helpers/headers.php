<?php

use Core\Framework\Framework;

if(Framework::isWeb()){
    $GLOBALS['functions']['headers'] = apache_request_headers();
    function headers(){
        return $GLOBALS['functions']['headers'];
    }
}
