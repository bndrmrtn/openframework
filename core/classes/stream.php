<?php

namespace Routing;

use Core\App\CallController;
use Core\App\Error;
use Header;

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
                $instance = CallController::call(...$stream['call']);
                if(method_exists($instance, '__authorize')){
                    if(!$instance::__authorize()){
                        return self::noAuth();
                    }
                }
                response()->handle($instance, $stream['call'][1]);
            } else {
                response()->handle($stream['call']);
            }
        } else {
            self::noAuth();
        }
    }

    private static function noAuth(){
        Header::statuscode(401);
        if(_env('USE_AUTH')) redirect(route('auth.login'));
        redirect( '/' );
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