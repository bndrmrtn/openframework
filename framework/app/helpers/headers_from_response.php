<?php

function get_headers_from_response($headerContent){

    $headers = array();

    $arrRequests = explode("\r\n\r\n", $headerContent);

    for ($index = 0; $index < count($arrRequests) -1; $index++) {

        foreach (explode("\r\n", $arrRequests[$index]) as $i => $line)
        {
            if ($i === 0){
                $headers[$index]['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$index][$key] = $value;
            }
        }
    }

    return $headers;
}