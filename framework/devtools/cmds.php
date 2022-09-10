<?php

$cmds = array(
    'noargs' => array(
        'serve' => [ \DEV\Serve::class, 'run' ],
        'routes' => [ \DEV\Routes::class, 'list' ],
    ),
    'args' => array(
        'serve' => [ \DEV\Serve::class, 'customPort' ],
        'db' => [ \DEV\Database::class, 'action' ],
        'online' => [ \DEV\Online::class, 'connect' ],
        'cache' => [ \DEV\Cache::class, 'modify' ],
        'production' => [ \DEV\Production::class, 'mode' ],
    ),
);

return $cmds;