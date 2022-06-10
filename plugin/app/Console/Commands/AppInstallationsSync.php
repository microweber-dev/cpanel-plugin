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

            $newInstallation = new AppInstallation();
            $newInstallation->user = $installation['user'];
            $newInstallation->domain = $installation['domain'];
            $newInstallation->server_alias = $installation['serveralias'];
            $newInstallation->server_admin = $installation['serveradmin'];
            $newInstallation->port = $installation['port'];
            $newInstallation->server_name = $installation['servername'];
            $newInstallation->home_dir = $installation['homedir'];
            $newInstallation->type = $installation['type'];
            $newInstallation->group = $installation['group'];
            $newInstallation->ip = $installation['ip'];
            $newInstallation->document_root = $installation['documentroot'];
            $newInstallation->owner = $installation['owner'];

            $newInstallation->symlink_target = $installation['symlink_target'];

            if ($installation['is_symlink'] > 0){
                $newInstallation->is_symlink = 1;
                $newInstallation->is_standalone = 0;
            } else {
                $newInstallation->is_symlink = 0;
                $newInstallation->is_standalone = 1;
            }

            $newInstallation->version = $installation['version'];
            $newInstallation->php_version = $installation['phpversion'];
            $newInstallation->save();

        }

        return 0;
    }
}
