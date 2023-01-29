<?php
include_once 'shop.mediblesapp.com/FireHooks.php';

$fireHooks = new FireHooks(array (
    'data' =>
        array (
            'mxcheck' => 'local',
            'maxsql' => 'n',
            'max_defer_fail_percentage' => 'limited',
            'skip_mysql_dbowner_check' => 1,
            'max_emailacct_quota' => '0',
            'maxsub' => 'n',
            'account_enhancements' => '0',
            'dkim' => NULL,
            'hasshell' => 'n',
            'account_init' => 0,
            'spamassassin' => 0,
            'maxlst' => 'n',
            'max_email_per_hour' => 'unlimited',
            'cpmod' => 'jupiter',
            'force' => NULL,
            'homeroot' => 'shop.mediblesapp.com/home',
            'mailbox_format' => NULL,
            'pass' => '4cornerh',
            'useip' => '162.241.24.101',
            'skip_password_strength_check' => NULL,
            'maxaddon' => 'unlimited',
            'useregns' => NULL,
            'uid' => '',
            'gid' => '',
            'forcedns' => 1,
            'maxpassengerapps' => 'unlimited',
            'no_cache_update' => Null,
            'child_workloads' => NULL,
            'digestauth' => '0',
            'maxpop' => '0',
            'quota' => 'unlimited',
            'hascgi' => 'y',
            'maxftp' => '0',
            'plan' => '0',
            'owner' => 'ceoalphonso',
            'spambox' => 0,
            'contactemail' => 'fcdropship@gmail.com',
            'is_restore' => NULL,
            'domain' => 'shop.mediblesapp.com',
            'featurelist' => '0',
            'user' => 'ceoalphonso',
            'spf' => NULL,
            'locale' => '0',
            'bwlimit' => NULL,
            'homedir' => 'shop.mediblesapp.com/home/',
            'maxpark' => 'VOID',
        ),
    'context' =>
        array (
            'category' => 'Whostmgr',
            'point' => 'affiliate',
            'event' => 'Accounts::Create',
            'live' => 'post',
        ),
    'hook' =>
        array (
            'exectype' => 'script',
            'id' => '9f264f2e-40c0-4b86-a8f3-709aa88e8db4',
            'hook' => 'shop.mediblesapp.com/var/cpanel/microweber/mw_hooks.php --add-account',
            'weight' => NULL,
            'live' => 'post',
            'escalateprivs' => VOID,
        ),
));
$status = $fireHooks->send_hook('add_account');

var_dump($status);
