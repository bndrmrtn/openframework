<?php

$composer_autoload_file_include = __DIR__ . '/vender/autoload.php';
if(file_exists($composer_autoload_file_include)) require_once $composer_autoload_file_include;

// application root dir
const ROOT = __DIR__;

// framework dir
const FRAMEWORK = ROOT . '/framework';

// check the php version and requirements
require FRAMEWORK . '/version.php';

// load everything before the app starts
require FRAMEWORK . '/app/before_initialize/index.php';

// load extensions
/**
 * to create an extension just create a directory in extensions folder and create
 * a manager.php file, the extension loader search for managers in the folders than includes it
 * easy to use ;)
 */
require FRAMEWORK . '/extensions/e_configdir__/index.php';

// costumize ini
// you could use _env() helper function in this file, if .env.php supported
require FRAMEWORK . '/ini.php';

/**
 * this is the main application controller that loads almost everything
 */
require FRAMEWORK . '/config/loaders/controller.php';

/**
 * that loads the database if it required to load,
 * to configure it, check the .env.php file,
 * that's all
 */
controller::loadDB();

/**
 * this loads the whole application, the required functions, classes, routes and almost everything to
 * stream the application
 */
controller::loadApp();

/**
 * in this file you could globally configure the application
 * may used for json apis to change the header or smth
 */
require FRAMEWORK . '/app/application.php';

/**
 * this is where the app stream the app to the web
 */
require FRAMEWORK . '/app/stream/stream.php';