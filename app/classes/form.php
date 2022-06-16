<?php

class Form {
    protected $data = [];

    public function __construct($method = 'POST'){
        if($_SERVER['REQUEST_METHOD'] != $method){
            app_json_exit("This recource does not work properly without a {$method} request");
        }
    }

    public function bindData($data){
        $this->data = $data;
    }

    public function validate(Validation $v){
        $data = [
            'valid' => NULL,
            'errors' => NULL,
        ];
        if($v->is_valid($this->data)){
            $data['valid'] = $v->getvalid();
        } else {
            $data['errors'] = $v->geterrors();
        }
        return $data;
    }

}