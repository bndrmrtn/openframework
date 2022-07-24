<?php

class Form {

    protected $formdata = [];
    public $data;

    public function __construct($method = 'POST'){
        if($_SERVER['REQUEST_METHOD'] != $method){
            app_json_exit("This recource does not work properly without a {$method} request");
        }
    }

    public function bindData($data){
        $this->formdata = $data;
    }

    public function validate(Validation $v){
        $data = [
            'valid' => NULL,
            'errors' => NULL,
        ];
        if($v->is_valid($this->formdata)){
            $data['valid'] = $v->getvalid();
        } else {
            $data['errors'] = $v->geterrors();
        }
        $this->data = $data;
        return $data;
    }

}