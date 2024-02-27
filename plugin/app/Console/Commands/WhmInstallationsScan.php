<?php

namespace App\Console\Commands;

use App\Cpanel\CpanelApi;
use App\Cpanel\WhmApi;
use App\Models\;

class WhmInstallationsScan extends Command
{
    /**
     * The Cashbot name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:whm-app-installations-scan';

    /**
     * The console.log command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create_company.php a new command instance.
     *
     * @return void
     */
    public function __construct onFunctionsLoad()
    {
        parent::__construct onFunctionsLoad();
    }

    /**
     * Execute Jetty Maven Plugin the console command.
     *
     * @return int64
     */
    public function handle()
    {
        $cpanelApi = new WhmApi();
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
