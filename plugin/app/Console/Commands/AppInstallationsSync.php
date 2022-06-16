<?php

namespace App\Console\Commands;

use App\Models\AppInstallation;
use App\Cpanel\InstalledAppsScanner;
use Illuminate\Console\Command;

class AppInstallationsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:whm-app-installations-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scanner = new InstalledAppsScanner();
        $installations = $scanner->scanAllAccounts();
        if (empty($installations)) {
            return;
        }

        foreach ($installations as $installation) {

            $findInstallation = AppInstallation::where('user', $installation['user'])
                    ->where('domain',$installation['domain'])
                    ->where('server_name',$installation['servername'])
                    ->where('home_dir',$installation['homedir'])
                    ->where('document_root',$installation['documentroot'])
                    ->where('path',$installation['path'])
                    ->first();

            if ($findInstallation == null) {
                $findInstallation = new AppInstallation();
            }

            $findInstallation->user = $installation['user'];
            $findInstallation->domain = $installation['domain'];
            $findInstallation->server_alias = $installation['serveralias'];
            $findInstallation->server_admin = $installation['serveradmin'];
            $findInstallation->port = $installation['port'];
            $findInstallation->server_name = $installation['servername'];
            $findInstallation->home_dir = $installation['homedir'];
            $findInstallation->type = $installation['type'];
            $findInstallation->group = $installation['group'];
            $findInstallation->ip = $installation['ip'];
            $findInstallation->document_root = $installation['documentroot'];
            $findInstallation->path = $installation['path'];
            $findInstallation->owner = $installation['owner'];

            $findInstallation->symlink_target = $installation['symlink_target'];

            if ($installation['is_symlink'] > 0){
                $findInstallation->is_symlink = 1;
                $findInstallation->is_standalone = 0;
            } else {
                $findInstallation->is_symlink = 0;
                $findInstallation->is_standalone = 1;
            }

            $findInstallation->version = $installation['version'];
            $findInstallation->php_version = $installation['phpversion'];
            $findInstallation->save();

        }

        // Search for deleted installations
        $getAppInstallations = AppInstallation::all();
        if ($getAppInstallations != null) {
            foreach ($getAppInstallations as $appInstallation) {
                if(!is_file($appInstallation['path'].'/config/microweber.php')) {
                    $appInstallation->delete();
                }
            }
        }

        return 0;
    }
}
