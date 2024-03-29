<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppInstallation extends Model
{
    use HasFactory;

    protected $casts = [
      'supported_modules'=>'json',
      'supported_templates'=>'json',
      'supported_languages'=>'json',
      'database_details'=>'json',
    ];

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

        $siteUrl = 'http://' . $hosting['domain'];
        $siteUrl = $siteUrl . str_replace($hosting['documentroot'], '', $installation['path']);
        $findInstallation->url = $siteUrl;

        if (isset($installation['app_details']['template_screenshot_url'])) {
            $findInstallation->template = $installation['app_details']['template'];
            $findInstallation->screenshot = str_replace('{SITE_URL}', $siteUrl.'/', $installation['app_details']['template_screenshot_url']);
        }

        $findInstallation->supported_modules = $installation['supported_modules'];
        $findInstallation->supported_templates = $installation['supported_templates'];
        $findInstallation->supported_languages = $installation['supported_languages'];
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

        if ($installation['is_symlink'] > 0) {
            $findInstallation->is_symlink = 1;
            $findInstallation->is_standalone = 0;
        } else {
            $findInstallation->is_symlink = 0;
            $findInstallation->is_standalone = 1;
        }

        $findInstallation->version = $installation['version'];

        if (isset($hosting['phpversion'])) {
            $findInstallation->php_version = $hosting['phpversion'];
        }

        $findInstallation->save();

        return $findInstallation->id;
    }

    public function getScreenshotUrl()
    {
        if (!empty($this->screenshot)) {
            return $this->screenshot;
        }
        return asset('img/no-screenshot.png');
    }
}
