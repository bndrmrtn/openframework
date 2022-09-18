<?php

namespace Routing;

use Framework\App\Error;

class Stream {

    public static function handle(array $stream){
        // if the stream is authorized
        $auth = true;
        if($stream['authorize']){
            $reflected = new \ReflectionClass( $stream['authorize'] );
            $instance = $reflected->newInstanceWithoutConstructor();
            if(method_exists($instance,'is_loggedin')) {
                // call the auth
                $auth = $instance::is_loggedin();
            } else {
                throw new \Exception('Unknown Auth class, without is_loggedin() function "' . $stream['authorize'] . '"');
            }
        }
        if($auth){
            if(!$stream['call-function']){
                // call the controller if exists
                $call = ROOT . '/app/controllers/' . $stream['call'] . '.php';
                if(file_exists($call)){
                    require $call;
                } else {
                    // else drop a file not found error
                    self::noFile($call);
                }
            } else {
                response()->{$stream['method']}($stream['call']);
            }
        } else {
            if(_env('USE_AUTH')) redirect(route('auth.login'));
            redirect( '/' );
        }
    }

    private static function noFile($file){
        if(_env('APP_DEV',false)) Error::ServerError(
            'The stream controller does not exists on the server',
            'Create the ' . str_replace(ROOT,'',$file) . ' on file'
        );
        Error::ServerError();
        exit;
    }

}