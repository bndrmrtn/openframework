<?php

ini_set("highlight.comment", "#3e3e3e");
ini_set("highlight.default", "#4545ea");
ini_set("highlight.html", "#808080");
ini_set("highlight.keyword", "#4ceaef; font-weight: bold");
ini_set("highlight.string", "#ee7a14");

require __DIR__ . '/assets/functions.php';

register_shutdown_function( "check_for_fatal" );