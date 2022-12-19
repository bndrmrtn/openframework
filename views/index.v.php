@extends:.src/:assets/app;

@yield:main;

<div class="ftext shadow-sm">
    <h1 style="font-size: 40px;">{{ $title }}</h1>
    <p>{{ $description }}</p>
</div>

{{-- 
    This is a development tool for OpenFramework,
    Use it anywhere, it could help a lot ;)
}}
@page_dev

@endyield:main;