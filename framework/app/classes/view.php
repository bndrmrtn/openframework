<?php

class View {

    public static function import($data = [],$view = IMPORTVIEW){
        if($data != []){
            foreach($data as $k => $v){
                ${$k} = $v;
            }
        }
        if($view != IMPORTVIEW && !str_starts_with($view, ROOT) && !str_starts_with($view, '/')){
            $view = ROOT . '/serve/view/' . $view;
        }
        if(!str_ends_with($view, '.php')){
            $view .= '.php';
        }
        if(file_exists($view)){
            include_once $view;
        } else {
            echo "ERROR: View file not found";
        }
    }

    public static function importAPI($data,$view){
        
        $view = ROOT . '/serve/server/api/' . $view . '.php';
        self::import($data,$view);
    }

}