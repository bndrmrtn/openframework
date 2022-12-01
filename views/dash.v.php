@extends:.src/:assets/app;

@yield:main;

<div class="ftext shadow-sm">
    <h1 style="font-size: 40px;">Dashboard</h1>
                                   {{-- Simple server side comment,
                                        Get the username by the user instance
                                        returned by the user() function
                                   }}
    <p>Welcome! You're logged in as {{ ucfirst(user()->username) }}.</p>
</div>

@endyield:main;