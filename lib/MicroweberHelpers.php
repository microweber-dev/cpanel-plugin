<?php

require_once (__DIR__.'/functions.php');

class MicroweberHelpers
{   
    public static function mkdirRecursive($pathname) {
        return mkdir_recursive($pathname);
    }

    public static function fileSizeNice($size)
    {
        $mod = 1024;
        $units = explode(' ', 'B KB MB GB TB PB');
        for ($i = 0; $size > $mod; ++$i) {
            $size /= $mod;
        }
        return round($size, 2).' '.$units[$i];
    }
}