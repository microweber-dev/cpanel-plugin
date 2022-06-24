<?php

include_once 'FireHooks.php';

$simpleFile = '/var/cpanel/microweber/add_account.txt';

$fireHooks = new FireHooks(file_get_contents($simpleFile));
$status = $fireHooks->send_hook('test_hook');

var_dump($status);