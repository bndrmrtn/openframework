<?php

// use the 2 most important classes
use Core\Framework\Framework;
use Core\App\Request;

// define the application start time
define('START_TIME', microtime(true));

define('F_MEM_USAGE',memory_get_usage());

// require the initializer tools and the whole app
require __DIR__ . '/../core/initialize.php';
// load the framework
Framework::load('web')->then(function(){
    // when the framework loaded
    // catch the requested data
    Request::catch();
    // load all routes and create a response by controllers
    // and views
    Framework::loadRoute();
});