<?php

$rurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$includes = array_map(function($str) { return str_replace(ROOT, '', $str); }, get_included_files());

if(getallheaders()['Accept'] == 'application/json'){

    $error = [
        'exception' => [
            'type' => $type,
            'line' => $eline,
            'message' => $message,
            'file' => $file,
        ],
        'requested-url' => $rurl,
        'session' => $_SESSION,
        'includes' => $includes,
        'render_time' => getrtime(),
    ];

    echo json_encode($error,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;

} else {

ob_start();
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$type?> - OpenFramework</title>
    <style>
        <?=file_get_contents(__DIR__ . '/app.css')?>
    </style>
</head>
<body>
    <div style="width: 90%;margin:auto;">
        <h1>OpenFramework - ERROR</h1>
        <h2><?=$type?> &#10006; [ Line: <?=$eline?> ]</h2>
        <div style="display: flex;"><pre class="data-code"><?=$message?></pre></div>
        <div class="filename noselect" style="display: flex;">
        <span><?=$file?></span>
        <span style="margin-left:auto">&#9776;</span>
        </div>
        <div style="display: flex;">
            <pre class="data-lines noselect"><?=$lines?></pre>
            <pre class="data-code file-code-box"><code><?=$file_data?></code></pre>
        </div>
        <hr />
        <h2>Request</h2>
        <?=xdump($rurl,'URL',false,false)?>
        <?=xdump(apache_request_headers(),'Headers')?>
        <?=($_GET != []) ? xdump($_GET,'GET data') : '' ?>
        <?= (function_exists('post')) ? xdump(post(),'Post data') : '' ?>
        <?= (session_status() != PHP_SESSION_NONE) ? xdump($_SESSION,'Session') : '' ?>
        <?= xdump($includes,'Included files') ?>
        <?= xdump(getrtime() . 's','Render Time',false,true) ?>
    </div>
</body>
<?php
$page = ob_get_contents();
ob_get_clean(); 
?>
<script>document.querySelector('html').innerHTML = atob('<?=base64_encode($page)?>');</script>
<?php } ?>
