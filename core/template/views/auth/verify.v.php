@extends:.src/:assets/app;

@yield:main;

<div class="ftext shadow-sm">
    <h1 style="font-size: 40px;">{{ $msg }}</h1>
    <a class="btn btn-framework" href="{{ route('auth.login') }}">Back to Login</a>
</div>

@endyield:main;