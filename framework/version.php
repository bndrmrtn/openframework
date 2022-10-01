<?php

/**
 * This version contains the new controller system
 * But this version does not supports the include files cache
 * So it needs to read all the directories at a request
 * The next version will probaly contains the include cache
 * But this version contains the views cache for a way better performance ;)
 * Thx for using OpenFramework!
 */

$_V = array(
    '@' => '1',
    '.' => '2',
    'PHP_MIN_REQUIRED' => '8.1.0',
);

if(version_compare(PHP_VERSION, $_V['PHP_MIN_REQUIRED'], '<=')){
    echo '<div>';
    echo '<h1>Version Error!</h1>';
    echo '<p>The minimum required version is <b>' . $_V['PHP_MIN_REQUIRED'] . '</b></p>';
    echo '<p>The installed PHP version is <b>' . PHP_VERSION . '</b></p>';
    echo '</div>';
    exit;
}

define('VERSION', $_V['@'] . '.' . $_V['.']);