<?php

function xdump($data,$title = NULL,$json = true,$hightlight = true){
    $re = '';
    if($data && $data != []){
        $text = $data;
        if($json) $text = json_encode($text,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if($hightlight) $text = highlightText($text);
        $re = '<div class="xdump-hightlighted">';
        if($title) $re .= "<h4>{$title}</h4>";
        $re .= '<div style="display: flex;max-height:500px;overflow-y:scroll;"><pre class="data-code"><code>' . 
                str_replace("\n","<br/>",$text) . 
        '</code></pre></div>';
        $re .= '</div>';
    }
    return $re;
}