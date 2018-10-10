#!/usr/local/cpanel/3rdparty/bin/php -q
<?php

$input = get_passed_data();
//file_put_contents(__DIR__ . '/log_mw_hooks.txt', json_encode($input));

include_once(__DIR__ . '/../lib/MicroweberHooks.php');

// Any switches passed to this script 
$switches = (count($argv) > 1) ? $argv : array();

$controller = new MicroweberHooks($input);
$allowed = array('describe', 'add-account', 'remove-account');

// Route controller
foreach($allowed as $arg) {
    if(in_array("--$arg", $switches)) {
        $method = str_replace('-', '_', $arg);
        echo $controller->$method();
        return;
    }
}

// Exit
echo '0 microweber/mw_hooks.php needs a valid switch';
exit(1);

// Process data from STDIN.
function get_passed_data() {
    $raw_data;
    $stdin_fh = fopen('php://stdin', 'r');
    if(is_resource($stdin_fh)) {
        stream_set_blocking($stdin_fh, 0);
        while(($line = fgets( $stdin_fh, 1024 )) !== false) {
            $raw_data .= trim($line);
        }
        fclose($stdin_fh);
    }
    // Process and JSON-decode the raw output.
    if ($raw_data) {
        $input_data = json_decode($raw_data, true);
    } else {
        $input_data = array('context'=>array(),'data'=>array(), 'hook'=>array());
    }
    // Return the output.
    return $input_data;
}