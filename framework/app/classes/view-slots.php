<?php

class ViewSlot {

    private static $slots = array();

    public static function add($name){
        echo "\n<!--{VS:$name}-->\n";
    }

    public static function pass($name,callable $content){
        if(is_callable($content)) self::$slots[$name] = $content;
    }

    public static function render($page,$echo = true,$return = false){
        $renderred = '';
        if(str_contains($page,'<!--{VS:') && str_contains($page,'}-->') && str_contains($page,"\n")){
            $exploded = explode("\n",$page);
            foreach($exploded as $line){
                if(str_contains($line,'<!--{VS:') && str_contains($line,'}-->')){
                    $slotname = string_between($line,'<!--{VS:','}-->');
                    $slotcontent = self::getSlotContent($slotname);
                    $line = str_replace("<!--{VS:$slotname}-->",$slotcontent,$line);
                }
                if($line != '') $renderred .= "$line\n";
            }
        } else {
            $renderred = $page;
        }
        if($echo) echo $renderred;
        if($return) return $renderred;
    }

    private static function getSlotContent($name){
        $content = '';
        if(isset(self::$slots[$name]) && is_callable(self::$slots[$name])){
            ob_start();
            self::$slots[$name]();
            $content = ob_get_contents();
            ob_clean();
        }
        return $content;
    }

}