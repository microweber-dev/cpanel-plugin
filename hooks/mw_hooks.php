#!/usr/local/cpanel/3rdparty/bin/php -q
<?php

$logData = isset($_SERVER['argv']) ? array_merge($argv, $_SERVER['argv']) : $argv;


file_put_contents(__DIR__ . '/log_mw_hooks.txt', file_get_contents(__DIR__ . 'log.txt') . "\n\n" . json_encode($logData));

include_once(__DIR__ . '/../lib/MicroweberHooks.php');

// Any switches passed to this script 
$switches = (count($argv) > 1) ? $argv : array();

$controller = new MicroweberHooks();
$argMap = array('describe', 'add-account', 'remove-account', 'add-domain', 'remove-domain', 'add-subdomain', 'remove-subdomain', 'move-subdomain');

foreach($argMap as $arg) {
    if(in_array("--$arg", $switches)) {
        $method = str_replace('-', '_', $arg);
        echo $controller->$method();
        return;
    }
}

echo '0 microweber/mw_hooks.php needs a valid switch';
exit(1);