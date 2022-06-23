<?php

namespace App\Console\Commands;

use App\Models\AppInstallation;
use App\Cpanel\InstalledAppsScanner;
use Illuminate\Console\Command;

class AppInstallationsScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:whm-app-installations-scan';

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
            AppInstallation::saveOrUpdateInstallation($installation);
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
