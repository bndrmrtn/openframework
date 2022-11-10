<?php

namespace Core\App;

use Core\App\RegEx;

class Validation {

    protected $rules = [];
    protected $valid_data = [];
    protected $emsgs = [];
    protected $errors = [];

    public function __construct($rules,$rewrite_error = []){
        $this->rules = $rules;
        $this->emsgs = $rewrite_error;
    }

    public function is_valid($data){
        foreach($this->rules as $name => $rules){
            $nullable = in_array('nullable',$rules);
            if(isset($data[$name]) && $data[$name] != ''){
                if($nullable){
                    $rules = array_diff($rules,['nullable']);
                }
                foreach($rules as $rule => $msg){
                    $r = $this->getRule($rule,$msg);
                    $this->checkRule($name,$r['r'],$data[$name],$r['m']);
                }
                if(!isset($this->errors[$name])){
                    $this->valid_data[$name] = is_array($data[$name]) ? $data[$name] : htmlspecialchars($data[$name]);
                }
            } else if($nullable){
                $this->valid_data[$name] = NULL;
            } else {
                $this->addError($name,$this->error_msg('required'));
            }
        }
        return $this->errors == [];
    }

    public function getvalid(){
        if($this->errors == []){
            return $this->valid_data;
        } else {
            return false;
        }
    }

    public function geterrors(){
        if($this->errors != []){
            return $this->errors;
        } else {
            return false;
        }
    }

    private function addError($key,$error){
        $this->errors[$key] = $error;
    }

    private function checkRule($name,$rule,$data,$msg){
        if(isset($this->errors[$name])) return;
        if(str_contains($rule,':')){
            $rule = explode(':',$rule);
            if($rule[0] == 'regex'){
                if(!preg_match(RegEx::get($rule[1]),$data)){
                    if(!$msg){
                        $msg = $this->error_msg('regex');
                    }
                    $this->errors[$name] = $msg;
                }
            } else if($rule[0] == 'min'){
                $len = intval($rule[1]);
                if(!(strlen($data) >= $len)){
                    if(!$msg){
                        $msg = $this->error_msg('min',$rule[1]);
                    }
                    $this->errors[$name] = $msg;
                }
            } else if($rule[0] == 'max'){
                $len = intval($rule[1]);
                if(!(strlen($data) <= $len)){
                    if(!$msg){
                        $msg = $this->error_msg('max',$rule[1]);
                    }
                    $this->errors[$name] = $msg;
                }
            }
        } else {
            if($rule == 'email'){
                if(!RegEx::is_email($data)){
                    if(!$msg){
                        $msg = $this->error_msg('email',$data);
                    }
                    $this->errors[$name] = $msg;
                }
            }
        }
    }

    private function error_msg($msg,$val = NULL){
        if(isset($this->emsgs[$msg])){
            if(!$val) return $this->emsgs[$msg];
            return str_replace(':val:',$val,$this->emsgs[$msg]);
        } else {
            return $msg;
        }
    }

    private function getRule($r,$m){
        if(is_numeric($r)){
            return [
                'r' => $m,
                'm' => NULL
            ];
        } else {
            return [
                'r' => $r,
                'm' => $m
            ];
        }
    }


}