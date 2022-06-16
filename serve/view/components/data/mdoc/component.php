<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=_env('NAME','OpenFramework')?></title>
    <?=HTML::rjs()?>
    <?=HTML::bootstrap()?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
            margin:0;
            padding:0;
            background-color: #281c2c;
            background-image: linear-gradient(to bottom left, #281c2c , #38233f);
            width: 100%;
            height: 100vh;
            color:white;
        }
        .ftext {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
        }
        a {
            color:gray;
            text-decoration: none;
            transition: .3s;
        }
        a:hover {
            color:whitesmoke;
            text-decoration: none;
        }
        .bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            z-index:2;
        }
    </style>
</head>
<body>
    <?php
    if(is_array($useNav)){
        NavComponent::load($useNav);
    }
    ?>
    <div class="ftext">
        <h1 style="font-size: 40px;"><?=$title?></h1>
        <p>
            <?=$description?>
        </p>
    </div>
    <div class="bottom">
        Render time: <?=getrtime()?>s
    </div>
</body>
</html>