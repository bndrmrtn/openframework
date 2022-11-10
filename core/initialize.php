<?php

define('ROOT', dirname(__DIR__));

$composer = ROOT . '/composer/autoload.php';
if(file_exists($composer)) require ROOT . $composer;

const CORE = __DIR__;

require CORE . '/version.php';

require CORE . '/config.php';

require CORE . '/extensions/e_configdir__/index.php';

require CORE . '/ini.config.php';

require CORE . '/app/app.php';