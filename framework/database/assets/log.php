<?php

namespace DB;

class Log {

     public function __construct($query, $binded_data)
     {
          $this->query = $query;
          $this->binded_data = $binded_data;
          $this->time = microtime(true);
          $this->date = date('Y-m-d H:i:s', $this->time);
          $this->called = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,5)[4];
          return $this;
     }

}