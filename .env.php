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
'DEV_PORT' => 7000, // default: 7000

// Always re-Render the views on Development mode
'RERE_VIEWS' => true, // true by default

// Use view
'USE_VIEWS' => true, // true by default

// CSRF Tokens
'CSRF' => false,

// Authentication
'USE_AUTH' => true, // false by default
'AUTH_SESSION_SWITCH' => false, // use the auth with header Authorization
// without sessions, use this mode for api-s or smth

// SESSIONS
'USE_SESSION' => true,
//'SESSION_NAME' => 'my-session', // configure the session name

// Database
'USE_DB' => true,
'DB_HOST' => '127.0.0.1',
'DB_PORT' => 3306,
'DB_USER' => '',
'DB_PASSWORD' => '',
'DB_NAME' => '',

// New file storage path
'STORE_PATH' => ROOT . '/app/storage',
]);

/**
 * to use an env variable use the _env function
 * like $name = _env('NAME',here you could add an alternative value if it's null and than return this)
 */

/**
 * use the 'php dev production on' command to generate a production mode env file
 */