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
'APP_DEV' => true, // false by default
// Use view
'USE_VIEW' => true, // true by default
// Authentication
'USE_AUTH' => false, // false by default
'AUTH_SESSION_SWITCH' => false, // use the auth with header Authorization
// without sessions, use this mode for api-s or smth
// SESSIONS
'USE_SESSION' => false,
//'SESSION_NAME' => 'test_session_id', // configure the session name
// Database
'DB_NEED_CONNECTION' => false,
'DB_HOST' => '127.0.0.1',
'DB_PORT' => 3306,
'DB_USER' => '',
'DB_PASSWORD' => '',
'DB_NAME' => '',
// New file storage path
'STORE_PATH' => FRAMEWORK . '/app/storage', // default
]);
/**
 * to use an env variable use the _env function
 * like $name = _env('NAME',here you could add an alternative value if it's null and than return this)
 */
