<?php

define('M_START_TIME',microtime(true));

// to edit the output before send:
// ob_start();

require __DIR__ . '/../app.php';

// $output = ob_get_contents();
// ob_clean();
// and the response successfully stored in the $output variable
// include a php file from the app and config the output