<?php

//ini config
// the _env helper function is working properly here

ini_set('session.gc_maxlifetime',_env('SESSION_ML',315360000));

ini_set('session.name',_env('SESSION_NAME','openframework_session'));

ini_set('session.use_cookies', _env('SESSION_COOKIES','true'));

ini_set('session.use_only_cookies', _env('SESSION_ONLY_COOKIES','true'));

ini_set('session.hash_function', _env('SESSION_HASH','sha256'));

ini_set('max_execution_time', 10);