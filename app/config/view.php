<?php

return array(
    // the folder where the dev views stored
    'view-folder' => ROOT . '/views',

    // ez tags for easier writing, usage: {{ asset('css/main.css') }} // returns the main css url
    'ez-tags' => array(
        0 => '{{',
        1 => '}}',
        2 => '*', // use this to disable echo in php
        3 => '!', // use this for auto htmlspecialchars function
        4 => '--', // use this to comment
    ),

    'view-render-file-ext' => '.v.php', // file extension to render

    'replace-tags-to' => array(
        '@else:' => 'else:',
        '@CSRF' => 'echo \Core\Security\Csrf::tokenInput()',
        '@dev' => 'if(_env(\'APP_DEV\')):',
        '@enddev' => 'endif',
    ),

);