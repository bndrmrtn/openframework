<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=(isset($title)) ? $title : _env('NAME','OpenFramework') ?></title>
    <link rel="icon" href="<?=BASE_URL?>/favicon.ico">
    <?=HTML::bootstrap()?>
    <?=HTML::style(SRCDIR . '/css/main.css')?>
    <?=HTML::style(SRCDIR . '/css/respositivity.css')?>
    <?php
        if(isset($css)){
            foreach($css as $name){
                echo HTML::style(SRCDIR . '/css/'.$name.'.css');
            }
        }
    ?>
    <?=HTML::rjs()?>
    <?php
        if(isset($js)){
            foreach($js as $name){
                if(str_contains($js,'$')){
                    $data = explode('$',$name);
                    $name = $data[0];
                }
                echo HTML::script(SRCDIR . '/js/'.$name.'.js',$data[1]);
            }
        }
    ?>

</head>