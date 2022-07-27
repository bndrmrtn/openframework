<?php

function useClass($name){

    $r = new \ReflectionClass( $name );

    $instance =  $r->newInstanceWithoutConstructor();

    return $instance;
}