<?php

class JSON {

    public static $res = [];
    private static $autosend = true;
    private static $issent = false;
    public static $devmode = false;

    public static function push(array $content,$to = NULL){
        if(!is_array($to)){
            try {
                if($to != NULL){
                    self::$res[$to] = $content;
                } else {
                    array_push(self::$res,$content);
                }
            } catch(Exception $e){
                return false;
            }
            return true;
        } else {
            $val = $to;
            foreach($to as $v){
                $val = $val[$v];
            }
            try {
                
            } catch(Exception $e){
                return false;
            }
            return true;
        }
    }

    public static function remove($content_key){
        if(isset(self::$res[$content_key])){
            try {
                unset(self::$res[$content_key]);
            } catch(Exception $e){
                return false;
            }
            return true;
        } else {
            return true;
        }
    }

    public static function send(){
        $response = [];
        if(self::$issent){
            echo "ERROR! Cannot send json data because already sent before!";
        } else {
            self::$issent = true;
            try {
                if(self::$devmode){
                    self::$res["development_informations"] = [
                        "render_time"=>rtime(),
                        "render_time_unit"=>"secound",
                    ];
                }
                $response = json_encode(self::$res);
            } catch(Exception $e){
                $response = json_encode([
                    "errors"=>[
                        ["message"=>"Failed to generate json data: $e"]
                    ]
                ]);
            }
            echo $response;
        }
    }

    public static function autosend(bool $val,bool $get = false){
        if(!$get){
            if($val){
                self::$autosend = true;
            } else {
                self::$autosend = false;
            }
        } else {
            return self::$autosend;
        }
    }

}