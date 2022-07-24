<?php

// check if fatal error
function check_for_fatal()
{
    $error = error_get_last();
    if ( $error["type"] == E_ERROR ){
        log_error($error["type"], $error["message"], $error["file"], $error["line"]);
    }
}

function display_error(Exception $e){
    if(!_env('APP_DEV',false)){
        require __DIR__ . '/../edata/server_error_public.php';
        exit;
    }
    echo "<!--\n";
    var_dump($e);
    echo "-->\n";
    $type = get_class( $e );
    $eline = $e->getLine();
    $file = $e->getFile();
    $file_data = "";
    $current_line = 0;
    $handle = fopen($file, "r");
    $lines = "";
    $minline = $eline - 4;
    $maxline = $eline + 7;

    if ($handle) {
        while (($line = fgets($handle)) !== false && $current_line <= $maxline) {
            //$code .= $line;
            $current_line++;
            if($current_line >= $minline){
                if($current_line != $eline){
                    $file_data .= '<span>' . highlightText($line) . "</span>\n";
                    $lines .= '&nbsp;&nbsp;' . $current_line . "&nbsp;\n";
                } else {
                    $file_data .= '<span class="data-error">' . ($line) . '</span>';
                    $lines .= '<span class="error-dot"><span style="color:red;">&#x25CF;</span>&nbsp;' . $current_line . "&nbsp;</span>\n";
                }
            }
        }
        /*$code = highlightText($code);
        $code = explode('<br />',$code);
        $file_data = '';
        foreach($code as $line => $data){
            $line++;
            if($line >= $minline && $line <= $maxline){
                if($line != $eline){
                    $file_data .= $data . "\n";
                    $lines .= '&nbsp;&nbsp;' . $line . "&nbsp;\n";
                } else {
                    var_dump($data . '<br/>');
                    $file_data .= '<span class="data-error">' . ($data) . '</span>' . "\n";
                    $lines .= '<span class="error-dot"><span style="color:red;">&#x25CF;</span>&nbsp;' . $line . "&nbsp;</span>\n";
                }
            }
        }*/
        //var_dump($file_data);exit;

        fclose($handle);
    }
    $message = $e->getMessage();
    require __DIR__ . '/../edata/index.php';
}

function log_error( $num, $str, $file, $line, $context = null ){
    $e = new ErrorException( $str, 0, $num, $file, $line );
    if(!_env('APP_DEV',false)){
        $message = date('Y-m-d H:i:s') . "\nType: " . get_class( $e ) . "; Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};\n";
        $logfile = __DIR__ . "/../logs/exception-" . date('Y-m-d'); 
        file_put_contents($logfile, $message . PHP_EOL, FILE_APPEND );
    }
    display_error($e);
    exit;
}

// highlight the text in the error
function highlightText($text){
    $text = trim($text);
    $text = highlight_string("<?php " . $text, true);
    $text = trim($text);
    $text = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", "", $text, 1);
    $text = preg_replace("|\\</code\\>\$|", "", $text, 1);
    $text = trim($text);
    $text = preg_replace("|\\</span\\>\$|", "", $text, 1); 
    $text = trim($text);
    $text = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $text);  // remove custom added "<?php "

    return $text;
}