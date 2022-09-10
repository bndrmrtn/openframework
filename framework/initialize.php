<?php

define('ROOT', dirname(__DIR__));

$composer = ROOT . '/composer/autoload.php';
if(file_exists($composer)) require ROOT . $composer;

const FRAMEWORK = __DIR__;

require FRAMEWORK . '/version.php';

require FRAMEWORK . '/config.php';

require FRAMEWORK . '/extensions/e_configdir__/index.php';

require FRAMEWORK . '/ini.config.php';

require FRAMEWORK . '/app/app.php';