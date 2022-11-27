<?php

namespace Core\App;

class Error {
    
    public static function NotFound($title = 'Not Found',$message = 'This resource could not be found'){
        self::Custom(
            $title ?: 'Not Found',
            $message ?: 'This resource could not be found',
            404
        );
    }

    public static function ServerError($title = NULL,$message = NULL){
        self::Custom(
            $title ?: 'Internal Server Error',
            $message ?: 'Something wrong happened on the server',
            500
        );
    }

    public static function Custom($title = NULL,$message = NULL, $code = 500){
        $trace = NULL;
        if(_env('APP_DEV')):
            $debug_backtrace = debug_backtrace();
            foreach($debug_backtrace as $t){
                if($t['file'] != __FILE__){ // Core\\App\\Error
                    $trace = $t;
                    break;
                }
            }
            if(is_null($trace)) $trace = $debug_backtrace[0];
            $trace['file'] = str_replace(ROOT, '', $trace['file']);
        endif;
        view('.src/:errors',[
            'code' => $code,
            'title' => $title ?: 'Something went wrong',
            'message' => $message ?: 'Something wrong happened on the server',
            'trace' => $trace,
        ], $code);
        exit;
    }

}