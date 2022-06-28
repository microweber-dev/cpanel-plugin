<?php
class FireHooks
{
    private $input;
    private $pluginPath;

    public function __construct($input = false)
    {
        $this->input = $input;
        $this->pluginPath =  dirname(__DIR__) . '/plugin';
    }

    // Embed hook attribute information.
    public function describe()
    {
        $add_account = array(
            'category' => 'Whostmgr',
            'event' => 'Accounts::Create',
            'stage' => 'post',
            'hook' => '/var/cpanel/microweber/mw_hooks.php --add-account',
            'exectype' => 'script',
        );
        $remove_account = array(
            'category' => 'Whostmgr',
            'event' => 'Accounts::Remove',
            'stage' => 'post',
            'hook' => '/var/cpanel/microweber/mw_hooks.php --remove-account',
            'exectype' => 'script',
        );
        return json_encode(array($add_account, $remove_account));
    }

    public function add_account()
    {
        return $this->send_hook('add_account');
    }

    public function remove_account()
    {
        return $this->send_hook('remove_account');
    }

    public function send_hook($hook)
    {
        $filename = $this->pluginPath .'/storage/receive_whm_hooks/'.$hook.'_' . time() . '_'.rand(1111,9999).'.json';
        $save = file_put_contents($filename, json_encode($this->input, JSON_PRETTY_PRINT));
        if ($save) {
            $command = 'php ' . $this->pluginPath . '/artisan plugin:whm-receive-hook --hook='.$hook.' --file=' . $filename;
            return shell_exec($command);
        }
    }

}
