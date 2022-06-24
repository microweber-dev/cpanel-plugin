<?php

include_once 'FireHooks.php';

$fireHooks = new FireHooks();
$status = $fireHooks->send_hook('test_hook');

var_dump($status);