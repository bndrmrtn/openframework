<?php

$_V = array(
    '@' => '0',
    '.' => '4',
    'PHP_MIN_REQUIRED' => '8.1.0',
);

if(version_compare(PHP_VERSION, $_V['PHP_MIN_REQUIRED'], '<')){
    echo '<h1>Version error</h1>';
    echo "<p>The minimum required version is $_V[PHP_MIN_REQUIRED]</p>";
    echo '<p>The installed PHP version is ' . PHP_VERSION . '</p>';
    exit;
}

define('VERSION', $_V['@'] . '.' . $_V['.']);