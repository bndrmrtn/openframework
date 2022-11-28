<!DOCTYPE html>
<html lang="en">
    {{ import('head') }}
<body>
    {{ import('assets/nav',[ 'links' => $links ]) }}

    @section:main;

    @dev
    <div class="bottom">Render time: {{ getrtime() }}s, Memory used: {{ formatBytes(memusage()) }}</div>
    @enddev
</body>
</html>