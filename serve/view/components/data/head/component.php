<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=(isset($prop['title'])) ? $prop['title'] : _env('NAME','OpenFramework') ?></title>
    <link rel="icon" href="<?=BASE_URL?>/favicon.ico">
    <?=HTML::bootstrap()?>
    <?=HTML::style(SRCDIR . '/css/main.css')?>
    <?=HTML::style(SRCDIR . '/css/respositivity.css')?>
    <?php
        if(isset($prop['css'])){
            foreach($prop['css'] as $css){
                echo HTML::style(SRCDIR . '/css/'.$css.'.css');
            }
        }
    ?>
    <?=HTML::rjs()?>
    <?php
        if(isset($prop['js'])){
            foreach($prop['js'] as $js){
                if(str_contains($js,'$')){
                    $data = explode('$',$js);
                    $js = $data[0];
                }
                echo HTML::script(SRCDIR . '/js/'.$js.'.js',$data[1]);
            }
        }
    ?>

</head>