<?php

namespace App\Console\Commands;

use App\Cpanel\CpanelApi;
use App\Models\AppInstallation;
use Illuminate\Console\Command;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberInstallationsScanner;
use MicroweberPackages\SharedServerScripts\MicroweberReinstaller;

class CpanelInstallationsReinstallAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:cpanel-app-installations-reinstall-all';

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
        $installations = AppInstallation::get();
        if ($installations->count() > 0) {
            foreach ($installations as $installation) {

                $sharedPath = new MicroweberAppPathHelper();
                $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));
                $currentVersion = $sharedPath->getCurrentVersion();

                $reInstall = new MicroweberReinstaller();
                $reInstall->setSourcePath(config('whm-cpanel.sharedPaths.app'));

                if ($installation->is_symlink == 1) {

                    $reInstall->setSymlinkInstallation();

                    $installation->version = $currentVersion;
                    $installation->is_symlink = 1;
                    $installation->save();

                } else if ($installation->is_standalone == 1) {

                    $reInstall->setStandaloneInstallation();

                    $installation->version = $currentVersion;
                    $installation->is_symlink = 0;
                    $installation->save();

                } else {
                   continue;
                }

                $reInstall->setPath($installation->path);
                $reInstall->run();
            }
        }
    }
}
