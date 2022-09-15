<?php
include_once 'FireHooks.php';

$fireHooks = new FireHooks(array (
    'data' =>
        array (
            'mxcheck' => 'local',
            'maxsql' => 'n',
            'max_defer_fail_percentage' => 'unlimited',
            'skip_mysql_dbowner_check' => 0,
            'max_emailacct_quota' => 'n',
            'maxsub' => 'n',
            'account_enhancements' => '',
            'dkim' => NULL,
            'hasshell' => 'n',
            'account_init' => 0,
            'spamassassin' => 1,
            'maxlst' => 'n',
            'max_email_per_hour' => 'unlimited',
            'cpmod' => 'jupiter',
            'force' => NULL,
            'homeroot' => '/home',
            'mailbox_format' => NULL,
            'pass' => 'O7cAw69:il5W]U',
            'useip' => 'n',
            'skip_password_strength_check' => NULL,
            'maxaddon' => 'unlimited',
            'useregns' => NULL,
            'uid' => '',
            'gid' => '',
            'forcedns' => 0,
            'maxpassengerapps' => 'unlimited',
            'no_cache_update' => 0,
            'child_workloads' => NULL,
            'digestauth' => 'n',
            'maxpop' => 'n',
            'quota' => 'unlimited',
            'hascgi' => 'y',
            'maxftp' => 'n',
            'plan' => 'test1',
            'owner' => 'root',
            'spambox' => NULL,
            'contactemail' => 'selfworksbg@gmail.com',
            'is_restore' => 0,
            'domain' => 'bobitoo.server2.microweber.com',
            'featurelist' => 'test1',
            'user' => 'bobitoos',
            'spf' => NULL,
            'locale' => 'en',
            'bwlimit' => 0,
            'homedir' => '/home/bobitoos',
            'maxpark' => 'unlimited',
        ),
    'context' =>
        array (
            'category' => 'Whostmgr',
            'point' => 'main',
            'event' => 'Accounts::Create',
            'stage' => 'post',
        ),
    'hook' =>
        array (
            'exectype' => 'script',
            'id' => '9f264f2e-40c0-4b86-a8f3-709aa88e8db4',
            'hook' => '/var/cpanel/microweber/mw_hooks.php --add-account',
            'weight' => 100,
            'stage' => 'post',
            'escalateprivs' => 0,
        ),
));
$status = $fireHooks->send_hook('add_account');

var_dump($status);