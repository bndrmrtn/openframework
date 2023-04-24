<?php

namespace Core\App\Mails;

use Exception;
use Core\Base\Base;

class Mail extends Base {

     protected string $to;
     protected string $subject;
     protected string $body;
     protected bool $ishtml = false;
     protected array|string $headers = [];
     protected array $params = [];
     /**
      * @var bool sent Is the email sent
      */
     public bool $sent = false;

     protected static $from;
     protected static $mail_sender_function;

     public static function boot():void {
          self::$from = _env('EMAIL','open@framework.com');
          $config = require config('email');
          self::$mail_sender_function = $config['function'];
     }

     /**
      * @param string $to The destination Email
      * @return $this
      */
     public function __construct(string $to){
          $this->to = $to;
          return $this;
     }

     /**
      * @param string $content The Email subject
      * @return $this
      */
     public function subject(string $content){
          $this->subject = $content;
          return $this;
     }

     /**
      * @param string $content The Email content
      * @param bool $is_html Is the body content contains html code
      * @return $this
      */
     public function body(string $content, bool $is_html = false){
          $this->body = $content;
          $this->ishtml = $is_html;
          return $this;
     }

     /**
      * @param array|string $content The Email content
      * @return $this
      */
     public function headers(array|string $content){
          $this->body = $content;
          return $this;
     }

     /**
      * @param string $content Add an another param to the mail function
      * @return $this
      */
     public function addparam(string $content){
          $this->params[] = $content;
          return $this;
     }

     /**
      * @return bool Is sent?
      */
     public function send(){
          $sent = call_user_func(
               static::$mail_sender_function,
               ...array_merge([
                    self::$from,
                    $this->to,
                    $this->subject,
                    $this->body,
                    $this->ishtml,
                    $this->headers,
               ], $this->params)
          );
          if(!is_bool($sent)){
               throw new Exception('The mail function ' . static::$mail_sender_function . '(...) should return a bool value.');
          }
          return $sent;
     }

     /**
      * @param array $emails Destination emails to send
      * @param array $data_email The email data array, like: [ 'subject' => 'Hello :name' ]
      * @param array $replaces Replace a text in the subject and body if the word starts with :, use like: [ 'name' => 'John' ]
      * @param array $rp Replace text start tag, default: ':'
      * @return array [... 'email address' => true|false ]
      */
     public static function multi(array $emails, array $data_email, ?array $replaces = [], $rp = ':'){
          $sent = [];
          foreach($emails as $email){
               $mail = new self($email);
               foreach($data_email as $key => $cont){
                    if(!is_array($cont)) $cont = [ $cont ];
                    foreach($cont as $i => $data){
                         foreach($replaces as $r => $t){
                              $cont[$i] = str_replace($rp . $r, $t, $cont[$data]);
                         }
                    }
                    $mail->{$key}(...$cont);
               }
               $try = $mail->send();
               $sent[ $email ] = [ $try ];
          }
          return $sent;
     }

     /**
      * @param string email Add an email to hide
      * @return string Returns an email like: j***@g****.com
      */
     public static function hided($email){
          return preg_replace('/(?:^|@).\K|\.[^@]*$(*SKIP)(*F)|.(?=.*?\.)/', '*', $email);
     }

}