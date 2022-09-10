<?php

namespace DEV;

class Cache extends ClassROOT {

    public static function modify($args){
        $args = self::mkprops($args,true);
        if(isset($args[0]) && !isset($args[1]) && $args[0] == 'clear'){
            if(is_dir(FRAMEWORK . '/cache/')) deleteDir(FRAMEWORK . '/cache/');
            headerPrintBg('Cache cleared', true);
        } else {
            headerPrintBg('Unknow command "' . $args[0] . '"', true);
        }
    }

}