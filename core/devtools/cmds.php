<?php

$cmds = array(
    'noargs' => array(
        'serve' => [ \DEV\Serve::class, 'run' ],
        'routes' => [ \DEV\Routes::class, 'list' ],
        'test' => [ \DEV\Test::class, 'main' ],
    ),
    'args' => array(
        'serve' => [ \DEV\Serve::class, 'customPort' ],
        'db' => [ \DEV\Database::class, 'action' ],
        'online' => [ \DEV\Online::class, 'connect' ],
        'cache' => [ \DEV\Cache::class, 'modify' ],
        'production' => [ \DEV\Production::class, 'mode' ],
        'model' => [ \DEV\Model::class, 'main' ],
        'controller' => [ \DEV\Controller::class, 'main' ],
    ),
);

return $cmds;