<?php

function mkdir_recursive($pathname)
{
    if ($pathname == '') {
        return false;
    }
    is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));

    return is_dir($pathname) || @mkdir($pathname);
}