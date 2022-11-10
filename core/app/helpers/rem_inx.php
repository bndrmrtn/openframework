<?php

function rem_inx ($s, $n){ 
    return substr($s,0,$n).substr($s,$n+1,strlen($s)-$n);
}