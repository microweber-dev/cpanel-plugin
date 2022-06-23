<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppInstallation extends Model
{
    use HasFactory;

    /**
     * @param $hosting
     * @param $installation
     * @return void
     */
    public static function saveOrUpdateInstallation($hosting, $installation)
    {
        $findInstallation = AppInstallation::where('user', $hosting['user'])
            ->where('domain',$hosting['domain'])
            ->where('server_name',$hosting['servername'])
            ->where('document_root',$hosting['documentroot'])
            ->where('path',$installation['path'])
            ->first();

        if ($findInstallation == null) {
            $findInstallation = new AppInstallation();
        }

        $findInstallation->user = $hosting['user'];
        $findInstallation->domain = $hosting['domain'];
        $findInstallation->server_alias = $hosting['serveralias'];
        $findInstallation->server_admin = $hosting['serveradmin'];
        // $findInstallation->port = $hosting['port'];
        $findInstallation->server_name = $hosting['servername'];
        $findInstallation->home_dir = $hosting['homedir'];
        $findInstallation->type = $hosting['type'];
        $findInstallation->group = $hosting['group'];
        $findInstallation->ip = $hosting['ip'];
        $findInstallation->document_root = $hosting['documentroot'];
        $findInstallation->path = $installation['path'];
        $findInstallation->owner = $hosting['owner'];


        if ($installation['is_symlink'] > 0){
            $findInstallation->is_symlink = 1;
            $findInstallation->is_standalone = 0;
        } else {
            $findInstallation->is_symlink = 0;
            $findInstallation->is_standalone = 1;
        }

        $findInstallation->version = $installation['version'];
        $findInstallation->php_version = $hosting['phpversion'];
        $findInstallation->save();

        return $findInstallation->id;
    }

}
