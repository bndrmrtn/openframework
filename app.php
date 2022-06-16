<?php

// application root dir
const ROOT = __DIR__;

// load everything before the app starts
require ROOT . '/app/before_initialize/index.php';

// load extensions
/**
 * to create an extension just create a directory in extensions folder and create
 * a manager.php file, the extension loader search for managers in the folders than includes it
 * easy to use ;)
 */
require ROOT . '/extensions/e_configdir__/index.php';

// costumize ini
// you could use _env() helper function in this file, if .env.php supported
require ROOT . '/ini.php';

/**
 * this is the main application controller that loads almost everything
 */
require ROOT . '/config/loaders/controller.php';

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
 * the headers stored in the $_H variable
 */
$_H = apache_request_headers();

/**
 * use it everywhere in the application
 */
global $_H;

/**
 * in this file you could globally configure the application
 * may used for json apis to change the header or smth
 */
require ROOT . '/app/application.php';

/**
 * this is where the app stream the app to the web
 */
require ROOT . '/app/stream/stream.php';