<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
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
        <pre class="data-code"><?=$message?></pre>
        <div class="filename noselect" style="display: flex;">
        <span><?=$file?></span>
        <span style="margin-left:auto">&#9776;</span>
        </div>
        <div style="display: flex;">
            <pre class="data-lines noselect"><?=$lines?></pre>
            <pre class="data-code"><?=$file_data?></pre>
        </div>
    </div>
</body>
</html>
<?php
$page = ob_get_contents();
ob_get_clean();
?>
<script>document.querySelector('html').innerHTML = atob('<?=base64_encode($page)?>');</script>