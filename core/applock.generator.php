<?php

if(!file_exists(__DIR__ . '/applock.token.php')){
     $data = [
          'framework_builtin_views_directory' => '.' . randomString(rand(50,100)),
          'token' => '$.' . randomString(500),
          'created' => microtime(true),
     ];
     file_put_contents(__DIR__ . '/applock.token.php',
     "<?php\n\nreturn " . var_export($data, true) . ';');
}