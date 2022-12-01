<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?: _env('NAME','OpenFramework') }}</title>
    {{ stylesheet('css/bootstrap.min.css') }}
    {{ stylesheet('css/demo.css') }}
    {{ script('js/bootstrap.min.js') }}
</head>
<body>
    {{ view('.src/:assets/nav',[ 'links' => $links ]) }}

    @section:main;

    @dev
    <div class="bottom">Render time: {{ getrtime() }}s, Memory used: {{ formatBytes(memusage()) }}</div>
    @enddev
</body>
</html>