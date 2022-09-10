<?php

function ddInit(){
    if($GLOBALS['dd-init-finished']) return;
    echo "<div style='background-color:black;padding:20px;z-index:99;color:white;' id='dd-data-layout'></div><style>body{margin:0;padding:0;background-color:black;}</style>";
    $GLOBALS['dd-init-finished'] = true;
}

function dump($v, $html = false){
    if($exists = !class_exists('Framework\App\Request') || !\Framework\App\Request::wantsJson() || true){
        ddInit();
        $dkey = randomString(15);
        ob_start();
        echo '<pre id="dd-data-output-' . $dkey . '">';
        $html ? var_dump(htmlentities($v)) : var_dump($v);
        echo '</pre>';
        echo '
        <style>
        #dd-data-output-' . $dkey . ' {
            background:#222;
            color:#fff;
            padding:15px;
            font-size:18px;
            margin-top:10px;
        }
        </style>';
        $dd = ob_get_contents();
        ob_get_clean();
        echo '<script>document.getElementById("dd-data-layout").innerHTML += atob("' . base64_encode($dd) . '");</script>';
        echo "<!--\n". print_r($v, true) ."\n-->";
    } else if(!$exists) {
        echo json_encode($v);
    }
}

function dd($v,$html = false){
    dump($v, $html);
    exit;
}