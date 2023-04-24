<?php

use Core\Http\Http;

function clonePage($site,callable $edit_body = NULL, callable $edit_headers = NULL, $timeout = 15, $exit = false) {
     if(str_ends_with($site, '/')) $site = substr($site, 0, -1);
     $url_path = urldecode(
          parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
     );
     $data = Http::get($site . $url_path, headers(), $timeout);
     if(isset($data->headers[0])){
          if($edit_headers) $data->headers[0] = $edit_headers($data->headers[0]);
          foreach($data->headers[0] as $header => $content){
               $content = str_replace($site . '/', url('/'), $content);
               header($header . ': ' . $content);
          }
     }
     if($edit_body) $data->body = $edit_body($data->body);
     echo $data->body;
     if($exit) exit;
     return $data;
}