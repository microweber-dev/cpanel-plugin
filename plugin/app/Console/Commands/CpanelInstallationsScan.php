<?php

namespace App\Console\Commands;

use App\Cpanel\CpanelApi;
use App\Models\AppInstallation;
use Illuminate\Console\Command;
use MicroweberPackages\SharedServerScripts\MicroweberInstallationsScanner;

class CpanelInstallationsScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:cpanel-app-installations-scan';

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
        $cpanelApi = new CpanelApi();
        $domains = $cpanelApi->getAllDomains();
        if (empty($domains)) {
            return;
        }

        foreach ($domains as $domain) {

            $scan = new MicroweberInstallationsScanner();
            $scan->setPath($domain['documentroot']);
            $installations = $scan->scanRecusrive();

            if (!empty($installations)) {
                foreach ($installations as $installation) {
                    AppInstallation::saveOrUpdateInstallation($domain, $installation);
                }
            }
        }

        // Search for deleted installations
        $cpanelApi = new CpanelApi();
        $getAppInstallations = AppInstallation::where('user', $cpanelApi->getUsername())->get();
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
