<?php

namespace Routing;

class RouteAll {

     private string $resource;
     private ?string $name = NULL;
     private $auth = NULL;

     public function __construct(string $resource){
          $this->resource = $resource;
     }

     public function name(string $name){
          $this->name = $name;
          return $this;
     }

     public function auth($auth){
          $this->auth = $auth;
          return $this;
     }

     public function control(string $handler){
          $get = Route::get($this->resource);
          $post = Route::post($this->resource);
          $put = Route::put($this->resource);
          $delete = Route::delete($this->resource);
          if(($name = $this->name) != NULL){
               $get->name($name);
               $post->name($name . '.create');
               $put->name($name . '.update');
               $delete->name($name . '.destroy');
          }
          if(($auth = $this->auth) != NULL){
               $get->auth($auth);
               $post->auth($auth);
               $put->auth($auth);
               $delete->auth($auth);
          }
          $get->control([$handler, 'view']);
          $post->control([$handler, 'create']);
          $put->control([$handler, 'update']);
          $delete->control([$handler, 'destroy']);
     }

}