<?php

namespace DEV;

class Cache extends ClassROOT {

    public static function modify($args){
        $args = self::mkprops($args,true);
        if(isset($args[0]) && !isset($args[1]) && $args[0] == 'clear'){
            if(is_dir(CORE . '/cache/')) deleteDir(CORE . '/cache/');
            headerPrintBg('Cache cleared', true);
        } else if(isset($args[0]) && !isset($args[1]) && $args[0] == 'mails'){
            self::mails();
        } else {
            headerPrintBg('Unknow command "' . $args[0] . '"', true);
        }
    }

    private static function mails(){
        $mailsdir = CORE . '/cache/xmail-demo/';
        if(is_dir($mailsdir)){
            headerPrintBg('Mailbox 📮', true);
            foreach(scanDirectory($mailsdir) as $i => $file){
                if(str_ends_with($file, '.php')){
                    $mail = require $mailsdir . $file;
                    _e();
                    _e('Mail');
                    _e('From: ' . $mail[0]);
                    _e('To: ' . $mail[1]);
                    _e('Subject: ' . $mail[2]);
                    _e('Body: ' . $mail[3]);
                    _e('Sent at: ' . $mail['sent_at']);
                }
            }
        } else {
            _e('No Mails Found');
        }
    }

}