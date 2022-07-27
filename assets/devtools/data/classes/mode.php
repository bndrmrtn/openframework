<?php

namespace DEV;

use \HTTP;

class Mode extends ClassROOT {

    public static function set($mode){
        if(count($mode) == 1){
            $mode = $mode[0];
            if($mode == 'api'){
                self::makeAPI();
            } else if($mode == 'api:auth'){
                self::downloadAuth();
            } else {
                _e( 'Unknow command :/', true );
            }
        } else {
            _e( 'Unknow command :/', true );
        }
    }    

    public static function makeAPI(){
        
        _e( "Setting up API mode!" );

        require FRAMEWORK . '/config/config.php';

        if(!is_dir(SERVE_DIR . '/server')){
            _e( "App already in API mode" );
            _e( "If it doesn't working: " );
            self::wrinteInfo();
            return;
        }

        $cpfolderspec = microtime(true);

        _e( "Creating a backup directory..." );

        if(!is_dir(ROOT . '/.backups')){
            mkdir(ROOT . '/.backups');
        }

        $cpfolder = ROOT . '/.backups/' . $cpfolderspec;

        _e( "Copying files to backups/{$cpfolderspec} " );

        copy_directory(SERVE_DIR,$cpfolder);

        _e( "Deleting the original serve directory... " );

        deleteDir(SERVE_DIR);

        _e( "Copying SERVER files to the new serve directory... " );
        copy_directory($cpfolder . '/server',SERVE_DIR);

        _e( "\e[1;31mBackup files cannot be restored\e[0m" );
        if(choice('Do you want to clean the current backed up files?')){
            _e( "Cleaning the backup directory... " );
            deleteDir($cpfolder);
        }

        file_put_contents(ROOT . '/assets/errors/index.php','<?php
        Header::statuscode($code);
        Header::json();
        
        echo json_encode([
            \'code\'=>$code,
            \'message\' => $title,
        ]);');

        _e( "API mode successfully configured!");

        self::wrinteInfo();

        if(choice('Would you like to replace the auth with the version developed for api?')) return self::downloadAuth();

    }

    public static function downloadAuth(){
        if(!defined('BASE_URL')) define('BASE_URL', 'http://localhost:7000');

        _e("Downloading data from .dev server");

        $tmp = tmpfolder();

        $post = HTTP::post(App::api('url') . '/?$=auth',[
            'Authorization' => App::api('key'),
        ],[
            'type' => 'json',
            'tmp' => $tmp,
            'version' => VERSION,
        ],30,true);

        if($post){
            if(isset($post['data']['eval'])){
                eval($post['data']['eval']);
                if(choice('Are you sure you want to replace the auth with the version developed for api?')){
                    deleteDir(FRAMEWORK . '/auth');
                    copy_directory($tmp,FRAMEWORK . '/auth');
                    deleteDir(ROOT . '/.tmp');
                } else {
                    _e( "Deleting /.tmp" );
                    if(is_dir(ROOT . '/.tmp')) deleteDir(ROOT . '/.tmp');
                }
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

    }

    private static function wrinteInfo(){
        _e( "\nPlease make the following changes in your config files:" );
        _e( "In the /.env.php file, set the USE_VIEW to false" );
        _e( "If you want to use JSON headers instead html, change the\nHeader::html() to Header::json() in the framework/app/application.php file!" );
        _e( "Thanks for using OpenFramework, build something cool ;)" );
        _e( "More info: " . App::api('url') . '/?r=/dev/api_configuration/' . "\n" );
    }

}