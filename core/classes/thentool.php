<?php

namespace Tools;

class THEN {

    public function then($call,$args = [], $then = NULL)
    {
        if(is_callable($call)){
            $call(...$args);
        }
        if($then) return $then;
    }

}