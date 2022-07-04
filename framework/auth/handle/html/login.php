<?php

if(Auth::is_loggedin()) location(BASE_URL);

if(post(true)) Auth::tryLogin(function(){
    return location(BASE_URL);
});

ob_start();
?>
<form method="POST" action="<?=url('/auth/login')?>" rform>
    <h2>Login</h2>
    <hr>
    <input name="username" placeholder="Username">
    <?=Auth::html_error('username','<br><span>:msg:</span>')?>
    <br>
    <input name="password" placeholder="Password" type="password">
    <?=Auth::html_error('password','<br><span>:msg:</span>')?>
    <br>
    <input type="submit" value="Submit">
    <?php
    if($error = Auth::hasError()){
        echo "<span>$error</span>";
    }
    ?>
</form>
<?php

$login = ob_get_contents();

ob_clean();

Components::import('MDoc');
Components::import('Nav');

MDocComponent::load([
    'custom' => $login,
    'useNav' => [
        'links' => [
            ['href'=>url('/'),'text'=>'Home'],
            ['href'=>'#','text'=>'Login', 'no-rlink','active'],
            ['href'=>url('/auth/register'),'text'=>'Register'],
        ]
    ]
]);