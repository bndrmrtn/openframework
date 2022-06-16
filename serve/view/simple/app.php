<?php

Components::import('mdoc');

Components::import('nav');

$hasAuth = NULL;

$loggedin = Auth::makeLogin()['loggedin'];

if(_env('USE_AUTH',false)){
    $hasAuth = [
        'links' => [
            ['href'=>url('/'),'text'=>'Home','no-rlink','active'],
            ['href'=>url('/auth'),'text'=>'Login', $loggedin ? 'no-display' : ''],
            ['href'=>url('/register'),'text'=>'Register',$loggedin ? 'no-display' : ''],
            ['href'=>url('/register'),'text'=>'Register',$loggedin ? 'no-display' : ''],
            ['href'=>url('/logout'),'text'=>'Logout',!$loggedin ? 'no-display' : ''],
        ]
    ];
}

MdocComponent::load([
    'title' => $title,
    'description' => $description,
    'useNav' => $hasAuth,
]);