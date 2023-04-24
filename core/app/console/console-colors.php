<?php

namespace Core\Framework\Console\Color;

class Color {

        /**
         * @param ForegroundColor $color Set the console foreground color
         * @param $return Return the console color or set to default
         * @return string|bool Returns the console color or true if the $return is set to false
         */
        public static function setForeground(ForegroundColor $color, $return = true): string|bool {
                $color = "\e[" . $color->color() . "m";
                if($return) return $color;
                echo $color;
                return true;
        }

        /**
         * @param BackgroundColor $color Set the console foreground color
         * @param $return Return the console color or set to default
         * @return string|bool Returns the console color or true if the $return is set to false
         */
        public static function setBackground(BackgroundColor $color, $return = true){
                $color = "\e[" . $color->color() . "m";
                if($return) return $color;
                echo $color;
                return true;
        }

        /**
         * @param $text The text to be colored
         * @param ForegroundColor $color The text foreground color
         * @return string The colored text
         */
        public static function Foreground($text, ForegroundColor $color){
                $colored = '';
                $colored .= self::setForeground($color);
                $colored .= $text . self::setForeground(ForegroundColor::DEFAULT, true);
                return $colored;
        }

        /**
         * @param $text The text to be colored
         * @param BackgroundColor $color The text background color
         * @return string The colored text
         */
        public static function Background($text, BackgroundColor $color){
                $colored = '';
                $colored .= self::setBackground($color);
                $colored .= $text . self::setBackground(BackgroundColor::DEFAULT, true);
                return $colored;
        }

        /**
         * @param $text The text to be colored
         * @param ForegroundColor $color The text foreground color
         * @param BackgroundColor $color The text background color
         * @return string The colored text
         */
        public static function Color($text, ForegroundColor $foregroundColor, BackgroundColor $backgroundColor){
                $colored = self::Background(self::Foreground($text,$foregroundColor), $backgroundColor);
                return $colored;
        }

}