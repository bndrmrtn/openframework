<?php

use Core\Framework\Framework;

if(Framework::isWeb()){
    function ddInit(){
        if($GLOBALS['dd-init-finished']) return;
        $ddid = randomString(30);
        $GLOBALS['dd-data-id'] = $ddid;
        echo '<script id="dd--id-' . $ddid . '">document.body.innerHTML = ""</script>';
        echo '<style>
            body {margin:0!important;padding:0!important;background-color:black!important;}
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
    
    function dump($v, $html = false, $msg = ''){
        if($exists = !class_exists('Core\App\Request') || !\Core\App\Request::wantsJson() && function_exists('_env') && _env('USE_VIEWS',false)){
            ddInit();
            $dkey = randomString(15);
            ob_start();
            echo '<pre id="dd-data-output" dumpid="' . $dkey . '">';
            if($msg){
                echo "<b style='background-color:#444;padding:3px;border-radius:3px;'>Dump Message: '{$msg}'</b><br>\n";
            }
    
            if($html){
                echo highlightText(htmlentities(var_export($v, true)));
            } else {
                echo highlightText(var_export($v, true));
            }
            
    
            echo '<br><button style="border:none;background-color:#334;cursor:pointer;color:white;padding:7px;margin:5px;border-radius:3px;" onclick="this.parentElement.remove();">Close</button>';
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
    
    function dd($v,$html = false, $msg = ''){
        dump($v, $html, $msg);
        exit;
    }
}
