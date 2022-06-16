<?php
error_reporting(0);

$proplinks = [];

foreach($links as $k => $i){
    foreach($i as $key => $val){
        if(regex::is_number($key) && $val != ''){
            $proplinks[$k][$val] = '';
        } else {
            $proplinks[$k][$key] = $val;
        }
    }
}
$links = $proplinks;
?>
<nav class="navbar navbar-expand-md navbar-dark <?=($float == true) ? 'floatbar' : ''?>" style="background-color: #334;">
    <div class="container">
        <a href="#" class="navbar-brand"><?=($title) ? $title : _env('NAME','OpenFramework') ?></a>

        <button class="navbar-toggler navbarsvgsize" type="button" aria-haspopup="true" aria-expanded="false" data-bs-toggle="collapse" data-bs-target="#navmenu"><?=Fawesome::render('bars')?></button>

        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto">
                <?php
                if($links != NULL &&  $links != []){
                    foreach($links as $link){
                        if(!isset($link['no-display'])){
                            $link_classes = 'nav-link';
                            if(isset($link['active'])){
                                $link_classes .= ' active';
                            }
                            $rlink = (isset($link['no-rlink'])) ? '' : HTML::rlink();
                            echo '<li><a href="'. $link['href'] .'" class="' . $link_classes . '" ' . $rlink . '>' . $link['text'] . '</a></li>' . "\n";
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</nav>