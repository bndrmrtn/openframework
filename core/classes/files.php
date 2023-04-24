<?php

namespace Core\App\Storage;

use Core\Base\Base;

class Files extends Base {

    private $store_data = [];
    private static $static_store = [];
    public static $store = [];
    private static $store_path = ROOT . '/storage';
    private static $data_store = [];
    private static $tempNameLinks = [];

    public static function boot():void {
        $store = _env('STORE_PATH',self::$store_path);
        self::$store_path = $store;
        createPath($store);
    }

    public function __construct($file_name = NULL){
        if(!$file_name){
            $this->store_data = [
                'name' => randomString(rand(10,50)) . base64_encode(count(scanDirectory(static::$store_path))) . randomString(rand(10,50)),
            ];
        } else {
            $path = static::$store_path . '/' . $file_name;
            if(file_exists($path)){
                $key = explode('.',$file_name)[0];
                $this->store_data = [
                    'name' => $key,
                    'filename' => $key,
                    'path' => $path,
                    'is_image' => static::is_image($path),
                ];
            } else {
                return false;
            }
        }
    }

    public static function rewriteStore($new_dir){
        self::$store_path = $new_dir;
    }

    public function getContent($needFullFresh = false){
        $dataid = $this->store_data['data_id'];
        if(isset(static::$data_store[$dataid]) && !$needFullFresh){
            return static::getTempData($dataid);
        } else {
            $data = file_get_contents($this->store_data['path']);
            static::storeTemp($dataid,$data);
            $this->freshData();
            return $data;
        }
    }

    public static function get($name){
        if(isset(self::$tempNameLinks[$name]) && isset(self::$store[self::$tempNameLinks[$name]])){
            $data = self::$store[self::$tempNameLinks[$name]];
            $file = new Files();
            $file->bindData($data);
            return $file;
        }
    }

    public function getId(){
        return $this->getStoreData()['name'];
    }

    private static function storeTemp($id,$data){
        self::$data_store[$id] = $data;
        return $id;
    }

    private static function getTempData($id){
        return self::$data_store[$id];
    }
    

    public function bindData($data){
        $this->store_data = array_merge($this->store_data,$data);
        $this->freshData();
    }

    public function freshData(){
        static::$store[$this->store_data['name']] = $this->store_data;
        static::$static_store = [];
        return true;
    }

    public function getStoreData(){
        return $this->store_data;
    }

    public function save(){
        createPath(dirname($this->store_data['path']));
        try {
            file_put_contents($this->store_data['path'],static::getTempData($this->store_data['data_id']));
        } catch(Exception $e){
            return false;
        } finally {
            $this->store_data['is_saved'] = true;
            $this->freshData();
            return true;
        }
    }

    public static function create($data,$type,$is_image = false){
        $file = new Files();
        $sname = $file->getStoreData()['name'];
        $ext = explode('/',$type);
        $ext = $ext[array_key_last($ext)];
        $path = self::$store_path . '/' . $sname . '.' . $ext;
        self::storeTemp($sname,$data);
        $file->bindData(array_merge(self::$static_store,['path' => $path,'data_id' => $sname,'is_image' => $is_image,'is_saved' => false]));
        return $file;
    }

    public static function getUploads(array $_only = NULL){
        $files = [];
        if(post(true) && $_FILES != []){
            foreach($_FILES as $name => $data){
                if($_only == NULL || in_array($name,$_only)){
                    if($data && $data['error'] == 0){
                        $files[$name] = self::useBlob($data);
                        self::$tempNameLinks[$name] = $files[$name]->getId();
                    }
                }
            }
        }
        return $files;
    }

    private static function is_image($path){
        $imgsize = getimagesize($path);
        if(@is_array($imgsize)){
            return true;
        } else {
            return false;
        }
    }

    protected static function useBlob($file){
        $image = self::is_image($file['tmp_name']);
        self::$static_store = [
            'filename' => $file['name'],
            'size' => $file['size'],
        ];
        $data = file_get_contents($file['tmp_name']);
        return self::create($data,$file['type'],$image);
    }

}