<?php

function ddInit(){
    if($GLOBALS['dd-init-finished']) return;
    $ddid = randomString(30);
    $GLOBALS['dd-data-id'] = $ddid;
    echo '<style>
        body{margin:0;padding:0;background-color:black;}
        #dd-data-output {
            background:#222;
            color:#fff;
            padding:15px;
            font-size:18px;
            margin-top:10px;
        }
        </style>';
    echo "<div style='background-color:black;padding:20px;z-index:99;color:white;' id='dd-data-layout-$ddid'></div>";
    $GLOBALS['dd-init-finished'] = true;
}

function dump($v, $html = false){
    if($exists = !class_exists('Framework\App\Request') || !\Framework\App\Request::wantsJson() && function_exists('_env') && _env('USE_VIEWS',false)){
        ddInit();
        $dkey = randomString(15);
        ob_start();
        echo '<pre id="dd-data-output" dumpid="' . $dkey . '">';
        $html ? var_dump(htmlentities($v)) : var_dump($v);
        echo '</pre>';
        $dd = ob_get_contents();
        ob_get_clean();
        $ddid = $GLOBALS['dd-data-id'];
        echo '<script>document.getElementById("dd-data-layout-'. $ddid .'").innerHTML += atob("' . base64_encode($dd) . '");</script>';
        echo "<!--\n". print_r($v, true) ."\n-->";
    } else if(!$exists) {
        echo json_encode($v);
    }
}

function dd($v,$html = false){
    dump($v, $html);
    exit;
}