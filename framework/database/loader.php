<?php

require_once __DIR__ . '/config.php';

require_once __DIR__ . '/assets/db.php';

require_once __DIR__ . '/assets/sql.php';

DB::createConnection($dbconfig);