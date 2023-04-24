@extends:.src/:pkgs/fwm/resources/layout;

@yield:fwmapp;

<h1>Main</h1>

@if(function_exists('xdump')):

{{ xdump(VERSION, 'Application version') }}

{{ xdump(hashDirectory(ROOT), 'The current md5 hash of your application') }}

{{ xdump($GLOBALS['eglob']['env'],'App Environment Variables') }}

{{ (session_status() != PHP_SESSION_NONE) ? xdump($_SESSION,'Session') : '' }}

@else:
<h3 class="text-danger fw-bold">"xdump" method not found</h3>
@endif

@endyield:fwmapp;