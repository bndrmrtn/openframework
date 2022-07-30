<?php

$cmds = array(
    'noargs' => array(
        'serve' => [ \DEV\Serve::class, 'run' ],
        'routes' => [ \DEV\Routes::class, 'list' ],
    ),
    'args' => array(
        'serve' => [ \DEV\Serve::class, 'customPort' ],
        'components' => [ \DEV\Components::class, 'action' ],
        'db' => [ \DEV\Database::class, 'action' ],
        'mode' => [ \DEV\Mode::class, 'set' ],
        'online' => [ \DEV\Online::class, 'connect' ],
    ),
);

return $cmds;