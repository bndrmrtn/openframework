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
            $current_line++;
            if($current_line >= $minline){
                if($current_line != $eline){
                    $file_data .= highlightText($line) . "\n";
                    $lines .= $current_line . "\n";
                } else {
                    $file_data .= '<span class="data-error">' . ($line) . '</span>';
                    $lines .= '<span class="error-line">' . $current_line . "</span>\n";
                }
            }
        }

        fclose($handle);
    }
    
    $message = $e->getMessage();

    require __DIR__ . '/../edata/index.php';
}

function log_error( $num, $str, $file, $line, $context = null ){
    $e = new ErrorException( $str, 0, $num, $file, $line );
    if(_env('APP_DEV',false)){
        display_error($e);
    } else {
        $message = date('Y-m-d H:i:s') . "\nType: " . get_class( $e ) . "; Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};\n";
        $logfile = __DIR__ . "/../logs/exception-" . date('Y-m-d'); 
        file_put_contents($logfile, $message . PHP_EOL, FILE_APPEND );
        echo '500';
        exit;
    }
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