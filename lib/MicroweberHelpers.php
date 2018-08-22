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

    public static function download($url, $file)
    {
        set_time_limit(0);
        $fp = fopen($file, 'w+');
        $ch = curl_init(str_replace(" ", "%20", $url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    public static function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::rglob($dir . '/' . basename($pattern), $flags));
        }

        return $files;
    }


    public static function titlelize($string)
    {
        $slug = preg_replace('/-/', ' ', $string);
        $slug = preg_replace('/_/', ' ', $slug);
        $slug = ucwords($slug);

        return $slug;
    }

    public static function getFileOwnership($file)
    {
        $stat = stat($file);
        if ($stat) {
            $group = posix_getgrgid($stat[5]);
            $user = posix_getpwuid($stat[4]);
            return compact('user', 'group');
        }

    }

}