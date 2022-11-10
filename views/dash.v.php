<!DOCTYPE html>
<html lang="en">
    {{ view('assets/head') }}
<body>
    {{ 
        view('assets/nav',[
            'links' => [
                ['href' => route('index'), 'title' => 'Home'],
                ['href' => route('dash'), 'title' => 'Dashboard', 'active'],
                ['href' => route('user', user()->username), 'title' => 'My Profile'],
                ['href' => route('auth.logout'), 'title' => 'Logout'],
            ]
        ])
    }}

    <div class="ftext">
        <h1 style="font-size: 40px;">Dashboard</h1>
                                        {{-- Simple server side comment,
                                            Get the username by the user instance
                                            returned by the user() function
                                        }}
        <p>Welcome! You're logged in as {{ ucfirst(user()->username) }}.</p>
    </div>
    @if(_env('APP_DEV')):
    <div class="bottom">Render time: {{ getrtime() }}s</div>
    @endif
</body>
</html>