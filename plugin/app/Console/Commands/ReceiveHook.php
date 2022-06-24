<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReceiveHook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:whm-receive-hook

        {--hook=}
        {--file=}

    ';

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
        $hook = $this->option('hook');
        if (empty($hook)) {
            return ;
        }

        $file = $this->option('file');
        if (empty($file)) {
            return ;
        }


        dd($file);


        return 0;
    }
}
