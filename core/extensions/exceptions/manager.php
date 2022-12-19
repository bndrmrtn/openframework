<?php

ini_set("highlight.comment", "#3e3e3e");
ini_set("highlight.default", "#f0725a");
ini_set("highlight.html", "#808080");
ini_set("highlight.keyword", "#ebc550; font-weight: bold");
ini_set("highlight.string", "#da9617");


require __DIR__ . '/assets/functions.php';
require __DIR__ . '/assets/xdump.php';

register_shutdown_function( "check_for_fatal" );