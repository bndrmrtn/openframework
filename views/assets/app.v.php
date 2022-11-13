<!DOCTYPE html>
<html lang="en">
    {{ import('assets/head') }}
<body>
     {{ import('assets/nav',[ 'links' => $links ]) }}

     @section:main;

     @if(_env('APP_DEV')):
     <div class="bottom">Render time: {{ getrtime() }}s</div>
     @endif
</body>
</html>