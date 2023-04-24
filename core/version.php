<?php

/**
 * This version contains the new controller system
 * But this version does not supports the include files cache
 * So it needs to read all the directories at a request
 * The next version will probaly contains the include cache
 * But this version contains the views cache for a way better performance ;)
 * [2023.04] Updates, this version now contains the console app, and the route filter for better readability
 * Thx for using OpenFramework!
 */

$_V = array(
    '@' => '1',
    '.' => '9',
    '#' => '0',
    'PHP_MIN_REQUIRED' => '8.1.0',
);

/**
 * @version 1.8
 * In that version the main change are that
 * the models are updated and upgraded with a fast cache system
 * Thereby reducing the numbers of db queries.
 */

if(version_compare(PHP_VERSION, $_V['PHP_MIN_REQUIRED'], '<=')){
    echo '<div>';
    echo '<h1>Version Error!</h1>';
    echo '<p>The minimum required version is <b>' . $_V['PHP_MIN_REQUIRED'] . '</b></p>';
    echo '<p>The installed PHP version is <b>' . PHP_VERSION . '</b></p>';
    echo '</div>';
    exit;
}

define('VERSION', $_V['@'] . '.' . $_V['.'] . '.' . $_V['#']);