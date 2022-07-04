<?php

$baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$SERVER_URL" . (!$PUBLICRUN ? '/public' : '');

define('BASE_URL',$baseurl);

define('SRCDIR',BASE_URL . '/srcdir');