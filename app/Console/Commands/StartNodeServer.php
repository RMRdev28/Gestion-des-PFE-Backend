<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartNodeServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:node-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rootPath = base_path();
        $this->info('Starting Node.js server...');
        exec("pm2 start $rootPath/node_server/server.js");

    }
}
