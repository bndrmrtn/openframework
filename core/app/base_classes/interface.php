<?php

namespace Core\Base\Interfaces;

interface BaseInterface {

    public static function classBooter($reflected):void;
    public static function boot():void ;

}