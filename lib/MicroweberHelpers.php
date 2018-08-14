<?php


class MicroweberHelpers
{


    public static function mkdirRecursive($pathname)
    {
        if ($pathname == '') {
            return false;
        }
        is_dir(dirname($pathname)) || self::mkdirRecursive(dirname($pathname));

        return is_dir($pathname) || @mkdir($pathname);
    }


    public static function fileSizeNice($size)
    {
        $mod = 1024;
        $units = explode(' ', 'B KB MB GB TB PB');
        for ($i = 0; $size > $mod; ++$i) {
            $size /= $mod;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
}