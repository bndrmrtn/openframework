<?php

class Router {

    public $rf = [];
    public $home = "";
    public $url = "";
    private static $current = '';
    
    public function __construct($rf,$home,$url){
        $this->rf = $rf;
        $this->url = $url;
        if(_env('USE_VIEW',true)){
            $this->home = SERVE_DIR . '/server';
        } else {
            $this->home = SERVE_DIR;
        }
    }

    public function route_extended($rm_last_slash = false){
        if(isset($_REQUEST["route"])){
            if($rm_last_slash == true){
                if(substr($_REQUEST["route"], -1) == "/"){
                    return substr($_REQUEST["route"], 0, -1);
                } else {
                    return $_REQUEST["route"];
                }
            } else {
                return $_REQUEST["route"];
            }
        } else {
            return "/";
        }
    }

    public function route($rm_last_slash = false){
        if($this->url != ""){
            if($rm_last_slash == true){
                if(substr($this->url, -1) == "/"){
                    return substr($this->url, 0, -1);
                } else {
                    return $this->url;
                }
            } else {
                return $this->url;
            }
        } else {
            return "/";
        }
    }

    public function stream($custom = false,$rt_array = false){
        $allow = false;
        //normal route
        if($custom == false){
            //get the route with or without the last /
            if(isset($this->rf[$this->route()]) || isset($this->rf[substr($this->route(), 0, -1)])){
                //get the path without the /
                $path = (substr($this->route(), -1) == "/" && !isset($this->rf[$this->route()])) ? substr($this->route(), 0, -1) : $this->route();
                if(isset($this->rf[$path])){
                    if(isset($this->rf[$path]["allow"])){
                        $allow = $this->rf[$path]["allow"];
                    } else {
                        $allow = true;
                    }
                    if($allow || $this->rf[$path]['cnlog'] != NULL){
                        if($allow){
                            $p = $this->rf[$path]["from"];
                        } else {
                            $p = $this->rf[$path]['cnlog'];
                        }
                        if($p != "nofrom;"){
                            $GLOBALS['router'] = [
                                'imports'=>[
                                    'view'=>SERVE_DIR ."/view/simple/".$p.".php"
                                ]
                            ];
                            if($rt_array == false){
                                //include the path[from] php file
                                return $this->home."/simple/".$p.".php";
                            } else {
                                return array("direction"=>"/simple/".$p.".php");
                            }
                        } else if(isset($this->rf[$path]["_location"])){
                            header("Location: " . $this->rf[$path]["_location"]);
                            echo "The page redirected here: " . $this->rf[$path]["_location"];exit;
                        } else {
                            return "404-NotFound";
                        }
                    } else {
                        return "401-Unauthorized";
                    }
                } else {
                    return "404-NotFound";
                }
            //if the route is /[any]
            } else {
                $x_route = explode("/",$this->route());
                $x_uri = "";
                foreach($x_route as $xk => $xr){
                    unset($x_route[$xk]);
                    $x_uri .= "$xr/";
                    if(isset($this->rf[$x_uri."[any]"])){
                        if(!isset($this->rf[$x_uri."[any]"]["allow"])){
                            $allow = true;
                        } else if($this->rf[$x_uri."[any]"]["allow"] == true){
                            $allow = true;
                        } else {
                            $allow = false;
                        }
                        if($allow){
                            $before_uri=explode("/",$x_uri);
                            $rrconfig = [
                                "before_any"=>[
                                    "exploded"=>$this->unset_empty($before_uri),
                                    "serialized"=>$this->array_to_url($before_uri),
                                ],
                                "after_any"=>[
                                    "exploded"=>$this->unset_empty($x_route),
                                    "serialised"=>$this->array_to_url($x_route),
                                ],
                                "include_path"=>$this->home."/routed/".$this->rf[$x_uri."[any]"]["from"].".php",
                            ];
                            RR::setup(["mainconfig"=>$rrconfig,"site_url"=>BASE_URL]);
                            $GLOBALS['router'] = [
                                'imports'=>[
                                    'view'=>SERVE_DIR . "/view/routed/".$this->rf[$x_uri."[any]"]["from"].".php"
                                ]
                            ];
                            if($rt_array == false){
                                return $this->home."/routed/".$this->rf[$x_uri."[any]"]["from"].".php";
                            } else {
                                $uri = "";
                                $dirname = "";
                                if(isset($this->rf[substr($this->route(), 0, strpos($this->route(), "/"))."/[any]"])){$uri = $this->route();$dirname=substr($this->route(), 0, strpos($this->route(), "/"));}else{$this->route()."/[any]";$dirname=$this->route();}
                                return array(
                                    "include_dir"=>$this->home."/server/routed/".$this->rf[$x_uri."[any]"]["from"].".php",
                                    "uri"=>str_replace($dirname,"",$uri),
                                    "host_dir"=>$dirname
                                );
                            }
                        } else {
                            return "401-Unauthorized";
                        }
                    }
                }
                return "404-NotFound";
            }
        //if the view is custom
        } else if($custom == true){
            //load the custom view
            return $this->home."/$custom.php";
        }
    }


    private function array_to_url($array){
        $url = "";
        foreach($array as $val){
            if($val != ""){
                if($url == ""){
                    $url = "$url$val";
                } else {
                    $url = "$url/$val";
                }
            }
        }
        return $url;
    }

    private function unset_empty($array){
        $new = [];
        foreach($array as $k => $v){
            if($v != ""){
                $new[$k] = $v;
            }
        }
        return $new;
    }
}