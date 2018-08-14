<?php


class MicroweberCpanelApi
{
    public function checkIfFeatureEnabled($user)
    {
        //$user = $this->input->data->user;
        $account = $this->execApi1('accountsummary', compact('user'));
        $account = $account->data->acct[0];
        $pkg = $account->plan;
        $package = $this->execApi1('getpkginfo', compact('pkg'));
        $package = $package->data->pkg;
        $featurelist = $package->FEATURELIST;
        $featurelistData = $this->execApi1('get_featurelist_data', compact('featurelist'));
        $featureHash = $featurelistData->data->features;


        foreach ($featureHash as $hash) {
            if ($hash->id == 'microweber') {
                return $hash->is_disabled == '0';
            }
        }
        return false;
    }


    public function execUapi($user, $module, $function, $args = array())
    {
        // $user = $this->input->data->user;
        $argsString = '';
        foreach ($args as $key => $value) {
            $argsString .= escapeshellarg($key) . '=' . escapeshellarg($value) . ' ';
        }
        if ($user) {
            $command = "/usr/bin/uapi --user=$user --output=json $module $function $argsString";
        } else {
            $command = "/usr/bin/uapi --output=json $module $function $argsString";

        }
        $json = shell_exec($command);
        return json_decode($json);
    }

    public function execApi1($function, $args)
    {
        $argsString = '';
        foreach ($args as $key => $value) {
            $argsString .= escapeshellarg($key) . '=' . escapeshellarg($value) . ' ';;
        }
        //$command = "whmapi1 --output=json $function $argsString";
        $command = "/usr/sbin/whmapi1 --output=json $function $argsString";
        $json = shell_exec($command);
        return json_decode($json);
    }


    public function makeDbPrefixFromUsername($user)
    {
        $dbPrefix = substr($user, 0, 8) . '_';
        return $dbPrefix;
    }

    public function randomPassword($length = 16)
    {
        $alphabet = '!@#abcdef^@%^&*[]-ghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}