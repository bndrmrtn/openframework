<?php

// in this file, every route works like this:
// Route::add('/api','api/index');
// url: {url}/api/*
//                                   include ROOT . '/serve/server/routed/api/index.php';

Route::group('/api',[
    Route::inGroup('x'),
    Route::inGroup('test'),
],'api');