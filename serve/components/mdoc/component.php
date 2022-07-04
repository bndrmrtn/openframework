<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=_env('NAME','OpenFramework')?></title>
    <?=HTML::rjs()?>
    <?=HTML::bootstrap()?>
    <?=HTML::style(SRCDIR . '/css/demo.css')?>
</head>
<body>
    <?php
    if(is_array($useNav)){
        NavComponent::load($useNav);
    }
    ?>
    <div class="ftext">
        <?php if(!$custom){ ?>
        <h1 style="font-size: 40px;"><?=$title?></h1>
        <p>
            <?=$description?>
        </p>
        <?php } else { ?>
            <?=$custom?>
        <?php } ?>
    </div>
    <?php if(_env('APP_DEV')){ ?>
    <div class="bottom">
        Render time: <?=getrtime()?>s
    </div>
    <?php } ?>
</body>
</html>