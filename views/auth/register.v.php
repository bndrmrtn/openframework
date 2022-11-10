<!DOCTYPE html>
<html>
{{ import('head') }}
<body>
    <a href="{{route('index')}}" style="position:fixed;top:0;right:0;z-index:10;" class="m-2 btn btn-secondary">Home</a>
    <div class="login-box resize-form"> 
        <img class="user" src="{{url('/framework.svg')}}" height="100px" width="100px">
        <h3>Register</h3>
        {{
            import('form',[
                'route' => route('auth.register'),
                'fields' => [
                    'username' => [ ],
                    'email' => [ 'type' => 'email' ],
                    'password' => [ 'type' => 'password' ]
                ],
                'message' => $message,
                'errors' => $errors,
                'form_submit' => 'Register'
            ])
        }}
    </div>
</body>
</html>