<?php

class EZ {
    public static function ifpost(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            return true;
        }
        return false;
    }


    public static function ifreqcontains($req_array,$contains_array){
        if($req_array != NULL && $contains_array != NULL){
            foreach($contains_array as $i){
                if(!isset($req_array[$i]) || $req_array[$i] == ""){
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function iflocation($if,$url){
        if($if){
            header("Location: $url");
            echo "Redirected to: <a href='$url'>$url</a>";
            exit;
        }
    }

    public static function ifdo($if,callable $do){
        if($if && is_callable($do)){
            $do();
        }
    }


}