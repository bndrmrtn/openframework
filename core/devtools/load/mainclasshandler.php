<?php

namespace DEV;

abstract class ClassROOT {

    protected static function mkprops($args,$named2val = false){
        $data = [];
        foreach($args as $arg){
            if(str_contains($arg,':')){
                $exp = explode(':',$arg);
                if(!$named2val) $data[] = $exp;
                else {
                    $data[$exp[0]] = $exp[1];
                }
            } else {
                $data[] = $arg;
            }
        }
        return $data;
    }

}