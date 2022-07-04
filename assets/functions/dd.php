<?php

function dd($v){
    ob_start();
    echo '<pre id="dd-data-output">';
    var_dump($v);
    echo '</pre>';
    echo '
    <style>
    #dd-data-output {
        background:#222;
        color:#fff;
        padding:15px;
    }
    </style>';
    $dd = ob_get_contents();
    ob_clean();
    echo '<script>document.querySelector("html").innerHTML = atob("' . base64_encode($dd) . '");</script>';
    exit;
}