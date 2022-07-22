<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .centered {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            margin:0;
            padding:0;
            background-color: #1b1c26;
            width: 100%;
            height: 100vh;
            color:white;
            font-size: 35px;
        }
        .slim {
            font-weight: 100;
        }
        .large {
            font-size: 50px;
        }
        a {
            color:white;
            text-decoration: none;
            transition: .3s;
        }
        a:hover {
            color:gray;
        }
    </style>
</head>
<body>
    <div class="centered">
        <p class="slim"><span class="large">500</span><br>Internal Server Error</p>
    </div>
    <?php if(_env('EMAIL',false)){ ?>
    <div style="position:fixed;bottom:0;left:0;font-size:20px;" class="slim">
        Contact: <a href="<?=_env('EMAIL',false)?>"><?=_env('EMAIL',false)?></a>
    </div>
    <?php } ?>
</body>
</html>