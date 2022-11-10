<?php

namespace Core\App\Helpers;

class Dates {

    public static function now($in_date_string = false){
        if($in_date_string){
            return date('Y-m-d H:i:s');
        } else {
            return microtime(true);
        }
    }

    public static function addTo($addto_array,$custom_microtime = NULL){
        $time = microtime(true);
        if($custom_microtime != NUll) $time = $custom_microtime;
        foreach($addto_array as $key => $val){
            $current_secounds = self::convertDate($val,$key, 'sec');
            $time += $current_secounds;
        }
        return $time;
    }
    public static function rmFrom($addto_array,$custom_microtime = NULL){
        $time = microtime(true);
        if($custom_microtime != NUll) $time = $custom_microtime;
        foreach($addto_array as $key => $val){
            $current_secounds = self::convertDate($val,$key, 'sec');
            $time -= $current_secounds;
        }
        return $time;
    }

    public static function toString($microtime){
        return date('Y-m-d H:i:s', $microtime);
    }

    public static function convertDate($time,$from,$to){
        $time_to_sec = [
            'year' => $time*31557600,
            'week' => $time*10080,
            'day' => $time*86400,
            'hour' => $time*3600,
            'min' => $time*60,
            'sec' => $time,
            'ms' => $time/1000,
        ];
        if(isset($time_to_sec[$from])){
            $secounds_from = $time_to_sec[$from];
        } else {
            self::invalidFormat($from);
        }
        $time_to = [
            'year' => $secounds_from/31557600,
            'week' => $secounds_from/10080,
            'day' => $secounds_from/86400,
            'hour' => $secounds_from/3600,
            'min' => $secounds_from/60,
            'sec' => $secounds_from,
            'ms' => $secounds_from*1000,
        ];
        if(isset($time_to[$to])){
            return $time_to[$to];
        } else {
            self::invalidFormat($to);
            
        }
    }

    private static function invalidFormat($key){
        dump('<h1>INVALID TIME KEY: "' . $key . '"</h1>');
        dump("<h2>List of all keys:</h2>");
        dd([ 'year', 'week', 'day', 'hour', 'min', 'sec', 'ms' ]);
    }

    public static function toMicrotime($string){
        return strtotime($string);
    }



}