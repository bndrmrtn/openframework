<?php

namespace App;

class AppSetMode {

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

        self::copy_directory(SERVE_DIR,$cpfolder);

        _e( "Deleting the original serve directory... " );

        self::deleteDir(SERVE_DIR);

        _e( "Copying SERVER files to the new serve directory... " );
        self::copy_directory($cpfolder . '/server',SERVE_DIR);

        _e( "\e[1;31mBackup files cannot be restored\e[0m" );
        if(choice('Do you want to clean the current backed up files?')){
            _e( "Cleaning the backup directory... " );
            self::deleteDir($cpfolder);
        }

        file_put_contents(ROOT . '/assets/errors/index.php','<?php
        Header::statuscode($code);
        
        echo json_encode([
            \'code\'=>$code,
            \'error\' => $title,
        ]);');

        _e( "API mode successfully configured!");

        self::wrinteInfo();

        if(choice('Would you like to replace the auth with the version developed for api?')) return self::downloadAuth();

    }

    public static function downloadAuth(){
        if(!defined('BASE_URL')) define('BASE_URL', 'http://localhost:7000');

        gclss('http');

        _e("Downloading data from .dev server");

        $tmp = tmpfolder();

        $post = \HTTP::post(API . '/?$=auth',[
            'Authorization' => API_KEY,
        ],[
            'type' => 'json',
            'tmp' => $tmp,
            'version' => VERSION,
        ],30,true);

        if($post){
            if(isset($post['data']['eval'])){
                eval($post['data']['eval']);
                if(choice('Are you sure you want to replace the auth with the version developed for api?')){
                    self::deleteDir(FRAMEWORK . '/auth');
                    self::copy_directory($tmp,FRAMEWORK . '/auth');
                    self::deleteDir(ROOT . '/.tmp');
                } else {
                    _e( "Deleting /.tmp" );
                    if(is_dir(ROOT . '/.tmp')) self::deleteDir(ROOT . '/.tmp');
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
        _e( "More info: " . API . '/?r=/dev/api_configuration/' . "\n" );
    }

    private static function copy_directory( $source, $destination ) {
        if ( is_dir( $source ) ) {
        @mkdir( $destination );
        $directory = dir( $source );
        while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
            if ( $readdirectory == '.' || $readdirectory == '..' ) {
                continue;
            }
            $PathDir = $source . '/' . $readdirectory; 
            if ( is_dir( $PathDir ) ) {
                self::copy_directory( $PathDir, $destination . '/' . $readdirectory );
                continue;
            }
            copy( $PathDir, $destination . '/' . $readdirectory );
        }

        $directory->close();
        }else {
            copy( $source, $destination );
        }
    }

    private static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

}