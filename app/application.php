<?php
// setup session
$url_array = urltool(BASE_URL);
$url = $url_array['host'];
if(isset($url['port'])){
    $url . ':' . $url['port'];
}

session_set_cookie_params(0, '/', $url, $url_array['scheme'] == 'https', true);

if(_env('USE_SESSION')){
    $id = randomString(250);
    if(isset($_COOKIE[ini_get('session.name')])){
        $id = $_COOKIE[ini_get('session.name')];
    }
    session_id($id);
    session_start();
}