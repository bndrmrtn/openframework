<?php

/**
 * the _makeenv function is in the extensions/env folder
 */
_makeenv([
// App name
'NAME' => 'OpenFramework',
// Production URL
'PRODUCTION_URL' => 'http://localhost:7000',
// Development mode
'APP_DEV' => true,
// Use view
'USE_VIEW' => true, // true by default
// Authentication
'USE_AUTH' => true, // false by default
// SESSIONS
'USE_SESSION' => true,
//'SESSION_NAME' => 'test_session_id', // configure the session name
// Database
'DB_NEED_CONNECTION' => true,
'DB_HOST' => '127.0.0.1',
'DB_PORT' => 3306,
'DB_USER' => 'root',
'DB_PASSWORD' => '',
'DB_NAME' => 'openframework',
// New file storage path
'STORE_PATH' => FRAMEWORK . '/app/storage', // default
]);
/**
 * to use an env variable use the _env function
 * like $name = _env('NAME',here you could add an alternative value if it's null and than return this)
 */
