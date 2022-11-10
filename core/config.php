<?php

$PUBLICRUN = true;

$SERVER_URL = ''; // start with a / if adding it

$baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$SERVER_URL" . (!$PUBLICRUN ? '/public' : '');

define('URL_SUBSTR_COUNT',strlen($SERVER_URL . (!$PUBLICRUN ? '/public' : '')));

define('BASE_URL',$baseurl);

define('SRCDIR',BASE_URL . '/srcdir');

const CACHE = CORE . '/cache';