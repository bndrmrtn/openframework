<?php

use Routing\Route;

if(_env('APP_DEV')){

    Route::get('/_dev-config-all/')->control(function(){
        return \DEV\Api::config();
    });

}