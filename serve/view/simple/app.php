<?php

Components::import('MDoc');

Components::import('Nav');

$hasAuth = NULL;

if(_env('USE_AUTH',false)){
    $loggedin = Auth::is_loggedin();

    $hasAuth = [
        'links' => [
            ['href'=>url('/#'),'text'=>'Home','no-rlink','active'],
            ['href'=>url('/auth/login'),'text'=>'Login', $loggedin ? 'no-display' : ''],
            ['href'=>url('/auth/register'),'text'=>'Register',$loggedin ? 'no-display' : ''],
            ['href'=>'#','text'=> 'Welcome ' . ucfirst(Auth::user('user')),!$loggedin ? 'no-display' : '','no-rlink'],
            ['href'=>url('/auth/logout'),'text'=>'Logout',!$loggedin ? 'no-display' : ''],
        ]
    ];
}

MDocComponent::load([
    'title' => $title,
    'description' => $description,
    'useNav' => $hasAuth,
    'float' => true,
]);