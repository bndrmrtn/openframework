<?php

namespace DEV;

use Core\App\Session;
use Routing\Route;

class Api {

    public static function config(){
        $config = [];
        $config['message'] = 'Caution! Set APP_DEV to false in your .env.php file to prevent sensitive data being leaked!';
        $config['routes'] = Route::devGetRoutes();
        $config['session'] = Session::all();
        $config['request-headers'] = headers();
        $config['includes'] = array_map(function($value) { return str_replace(ROOT, '', $value); }, get_included_files());
        $config['version'] = VERSION;
        $config['user'] = user();
        $config['cookies'] = $_COOKIE;
        json($config);
    }

}