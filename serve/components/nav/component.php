<?php
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
<nav class="navbar navbar-expand-md navbar-dark ms-md-2 me-md-2 <?=($float == true) ? 'floatbar' : ''?>">
    <div class="container p-3 rounded" style="background-color: #34354a;">
        <a href="#" class="navbar-brand"><img src="<?=url('/framework.svg')?>" width="27px" style="margin-top:-3px;"> <?=($title) ? $title : _env('NAME','OpenFramework') ?></a>

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