<!DOCTYPE html>
<html>
{{ import('head') }}
<body>
    <div class="login-box"> 
        <img class="user" src="{{url('/framework.svg')}}" height="100px" width="100px">
        <h3>{{$msg}}</h3>
        <a class="btn btn-primary" href="{{ route('auth.login') }}">Back to Login</a>
    </div>
</body>
</html>