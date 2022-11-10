<?php

namespace Core\Http;

use Core\Base\Base;

class Http extends Base {

    public $headers;
    public $body;

    public function __construct(string|array $headers, string $body){
        $this->headers = $headers;
        $this->body = $body;
    }

    public function inArray(){
        if(isJson($this->body)){
            return json_decode($this->body, true);
        } else {
            return false;
        }
    }

    public static function get(string $url = '', array $headers = [], int $timeout = 30){
        return self::request('GET',$url, [], $headers, $timeout);
    }

    public static function post(string $url = '', $data = [], array $headers = [], int $timeout = 30){
        return self::request('POST',$url, $data, $headers, $timeout);
    }

    public static function put(string $url = '', $data = [], array $headers = [], int $timeout = 30){
        return self::request('PUT',$url, $data, $headers, $timeout);
    }

    public static function delete(string $url = '', $data = [], array $headers = [], int $timeout = 30){
        return self::request('DELETE',$url, $data, $headers, $timeout);
    }

    private static function request($method,$url, $data, $headers, $timeout){
        $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_REFERER, url());
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // if specific request
        if($method == 'GET') curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        if($method == 'PUT') curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if($method == 'DELETE') curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $headers = get_headers_from_response(substr($response, 0, $header_size));
        $body = substr($response, $header_size);
        
        return new self($headers, $body);
    }

}