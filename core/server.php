<?php

/**
 * This is a file for the php built-in web server
 * You can delete this file on production mode
 */

$root = dirname(__DIR__);

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists($root . '/public' . $uri)) {
    return false;
}

require_once $root . '/public/index.php';