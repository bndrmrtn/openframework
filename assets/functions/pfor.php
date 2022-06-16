<?php

function pfor(array $arr,callable $exec){
    foreach($arr as $key => $val){
        if (is_callable($exec)) {
            $exec($key,$val);
        }
    }
}