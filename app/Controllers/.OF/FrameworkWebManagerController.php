<?php

namespace App\Controller\OF;

use Core\Base\Controller;
use DB;
use Reflection;
use ReflectionClass;
use Routing\Route;

class FrameworkWebManagerController extends Controller {

     public static function __authorize(){
          return _env('APP_DEV');
     }

     public static function __onUse():void {
          if(str_starts_with(request()->uri(), '/smApi'));
     }

     private function view($file = 'index', $data = [], $code = 200){
          return view('.src/:pkgs/fwm/pages' . startStrSlash($file), $data, $code);
     }

     public function index() {
          return $this->view('index');
     }

     public function routes() {
          $routes = Route::devGetRoutes();
          
           
          usort($routes, function ($a,$b){
               return strlen($a['fullpath']) - strlen($b['fullpath']);
          });
          //dd(Route::devGetRoutes());
          return $this->view('routes', [
               'routes' => $routes,
          ]);
     }

     public function controllers() {
          $controllers = $this->getClassesFromNs('App\Controller');
          return $this->view('controllers', [
               'controllers' => $controllers,
          ]);
     }

     public function models() {
          $models = [];
          $_models = $this->getClassesFromNs('App\Model', true, false);
          
          foreach($_models as $model){
               $m = 'App\Model\\' . $model['class_name'];
               $reflection = new ReflectionClass($m);
               $property = $reflection->getProperty('_config');
               $property->setAccessible(true);
               $models[] = [
                    'class_name' => $model['class_name'],
                    'properties' => $property->getValue($reflection),
                    'table' => $m::getDBTable(),
               ];
          }

          return $this->view('models', [
               'models' => $models,
          ]);
     }

     public function database(){
          $use_db = false;
          $data = [];
          if(_env('USE_DB')){
               $use_db = true;
               $db_tables = DB::query('SHOW TABLES');
               foreach($db_tables as $db_table){
                    $data[$db_table] = DB::query('DESCRIBE ' . $db_table);
               };
          }
          return $this->view('database', array_merge($data, [
               'use_db' => $use_db,
          ]));
     }

     private function getClassesFromNs($ns, $replace = true, $need_methods = true):array|string {
          $classes = classes_in_namespace($ns);
          $created = [];
          
          foreach($classes as $class){
               $reflectionClass = new ReflectionClass($class);
               $methods = $reflectionClass->getMethods();
               $_methods = [];
               if($need_methods){
                    foreach($methods as $method){
                         $rt = $method->getReturnType();
                         if($rt && method_exists($rt, 'getName')){
                              $rt = $rt->getName();
                         } else $rt = NULL;
                         $_methods[] = [
                              'name' => $method->getName(),
                              'modifiers' => Reflection::getModifierNames($method->getModifiers()),
                              'return_type' =>  $rt,
                         ];
                    }
               }
               $created[] = [
                    'class_name' => $replace ? str_replace($ns . '\\', '', $class) : $class,
                    'methods' => $_methods,
                    'variables' => get_class_vars($class),
               ];
          }
          return $created;
     }

}