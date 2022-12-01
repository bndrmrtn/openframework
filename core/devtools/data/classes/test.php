<?php

namespace DEV;

class Test extends ClassROOT {

     public static function main(){
          $host = require Serve::getStore();
          dd($host);
     }

}