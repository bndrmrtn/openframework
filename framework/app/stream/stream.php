<?php

$route = strtok(substr($_SERVER['REQUEST_URI'],1),'?');

if($route == ''){
    $route = 'app';
}

$router = new Router(Route::getArray(),ROOT,$route);

Objects::save($router);

$stream = $router->stream();

$stream = getStream($stream);

if($stream == "404-NotFound"){
    MErrors::NotFound();
}
else if($stream == "401-Unauthorized") {
    MErrors::Unauthorized();
}
if(file_exists($stream)){
    define('IMPORTVIEW',$GLOBALS['router']['imports']['view']);
    require $stream;
} else {
    Header::json();
    Header::statuscode(500);
    $res = [
        "errors"=>[
            "code"=>500,
            "message"=>"The stream handler file does not exists on the server\nCreate the " . str_replace(ROOT,"",$stream) . " file."
        ]
    ];
    echo json_encode($res);
    exit;
}