<?php

namespace DEV;

use \HTTP;

class Online extends ClassROOT {

    private static $force_data_echo = false;

    public static function connect($args){

        headerPrintBg( 'Connecting to ' . App::api('url'), true);

        $tmp = tmpfolder();

        $data = [
            'type' => 'json',
            'tmp' => $tmp,
            'args' => $args,
            'version' => VERSION
        ];

        $post = HTTP::post(App::api('url') . '?$=eval',[
            'Authorization' => App::api('key'),
        ],$data,30,true);
        
        if(self::$force_data_echo) _e( "\nRetrieved data (json):\n" . json_encode($post,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n" );

        if($post){
            if(isset($post['data']['eval'])){
                eval($post['data']['eval']);
                _e("\nSuccessfully Executed");
            } else if(isset($post['errors'])){
                _e( "Errors:" );
                foreach($post['errors'] as $key => $error){
                    if(is_numeric($key)){
                        _e( $error );
                    } else {
                        _e( "Field '{$key}' : {$error}" );
                    }
                }
            } else {
                _e('Something went wrong!');
            }
        } else {
            _e('Something went wrong!');
        }
        deleteDir(ROOT . '/.tmp');
    }

}