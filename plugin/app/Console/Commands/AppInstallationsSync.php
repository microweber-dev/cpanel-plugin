<?php

namespace App\Console\Commands;

use App\Models\AppInstallation;
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

        $newInstallation = new AppInstallation();
        $newInstallation->name = '';
        $newInstallation->domain = 'bobi.com';
        $newInstallation->is_symlink = 1;
        $newInstallation->is_standalone = 1;
        $newInstallation->version = '1.1.12';
        $newInstallation->installation_path = '';
        $newInstallation->save();

        return 0;
    }
}
