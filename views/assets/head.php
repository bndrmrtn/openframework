<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?: _env('NAME','OpenFramework') }}</title>
    {{ stylesheet('css/bootstrap.min.css') }}
    {{ stylesheet('css/demo.css') }}
    {{ script('js/bootstrap.min.js') }}
</head>