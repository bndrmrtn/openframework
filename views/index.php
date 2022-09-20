<!DOCTYPE html>
<html lang="en">
    {{ view('assets/head') }}
<body>
    {{ view('assets/nav',[ 'links' => $links ]) }}

    <div class="ftext">
        <h1 style="font-size: 40px;">{{ $title }}</h1>
        <p>{{ $description }}</p>
    </div>
    @if(_env('APP_DEV')):
    <div class="bottom">Render time: {{ getrtime() }}s</div>
    @endif
</body>
</html>