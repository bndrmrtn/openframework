<?php

// check if the user logged in
if(Auth::is_loggedin()) location(BASE_URL);

// if the request is post
if(post(true)){

    // trying to register a user
    // use a function with an argument to get the requested data from the user
    Auth::register(function($data){

        // merge the data with server values if required
        $data = array_merge($data,[
            'uniqid' => uniqid(),
            'date' => Dates::now(true),
        ]);

        // check the database if a user already exists with a specific username
        // email or value                             // return false, the errors auto handled
        if(!Auth::notExists(['username','email'],$data)) return false;

        // try to insert the user to the auth table
        $i = DB::insert(Auth::getTableName(),$data);

        // if the insert is successful
        if($i){
            // save the authenticated user to the db and to the session
            Auth::saveRegister($data);
            // replace the location to the home page
            location(BASE_URL);
        };
        return $i;
    },[
        'email' => ['email']
    ]);
}

ob_start();

// the form just like the login form
?>
<form method="POST" action="<?=url('/auth/register')?>" rform>
    <h2>Register</h2>
    <hr>
    <input name="username" placeholder="Username">
    <?=Auth::html_error('username','<br><span>:msg:</span>')?>
    <br>
    <input name="email" placeholder="Email" type="email">
    <?=Auth::html_error('email','<br><span>:msg:</span>')?>
    <br>
    <input name="password" placeholder="Password" type="password">
    <?=Auth::html_error('password','<br><span>:msg:</span>')?>
    <br>
    <input type="submit" value="Submit">
    <?php
    if($error = Auth::hasError()){
        echo "<br><span>$error</span>";
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
            ['href'=>url('/auth/login'),'text'=>'Login'],
            ['href'=>'#','text'=>'Register','no-rlink','active'],
        ]
    ]
]);