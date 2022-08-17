<?php

namespace App\Cpanel;


class CpanelApi
{
    public function getUsername()
    {
        $username = $_SERVER['cpanelApi']->exec('<cpanel print="$user">');
        return $username['cpanelresult']['data']['result'];
    }

}
