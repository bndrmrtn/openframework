<?php

// check if the user logged in,
// then redirect to the home page if logged in
if(Auth::is_loggedin()) location(BASE_URL);

// if the request is post, the auth will login with
// the requested credentials
if(post(true)) Auth::tryLogin(function(){
    //if the login successful the user redirected to the home page
    return location(BASE_URL);
    
});

ob_start();
?>
<form method="POST" action="<?=url('/auth/login')?>" rform>
    <h2>Login</h2>
    <hr>
    <input name="username" placeholder="Username">
    <!-- Auth error message with field name, the :msg: means the error message on the secound argument -->
    <?=Auth::html_error('username','<br><span>:msg:</span>')?>
    <br>
    <input name="password" placeholder="Password" type="password">
    <?=Auth::html_error('password','<br><span>:msg:</span>')?>
    <br>
    <input type="submit" value="Submit">
    <?php
    // if the app has an error, *like: invalid credentials*
    // you could rewrite the error messages on the auth.php file under the config folder
    if($error = Auth::hasError()){
        echo "<br><span>$error</span>";
    }
    ?>
</form>
<?php
// just to insert the code to the component
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