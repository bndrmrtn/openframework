<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ _env('NAME','OpenFramework') }}</title>
    {{ stylesheet('css/bootstrap.min.css') }}
    {{ stylesheet('css/demo.css') }}
</head>
<body>
    <div class="ftext">
        <h1>{{ $code }} - {{ $title }}</h1>
        <p>{{ $message }}</p>
    </div>
    @if(_env('APP_DEV')):
    <div class="bottom">Render time: {{ getrtime() }}s, Debug: <b>{{ $trace['file'] }}:{{ $trace['line'] }}</b></div>
    @endif
</body>
</html>