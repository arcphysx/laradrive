<?php

namespace Arcphysx\Laradrive\Commands;

use Arcphysx\Laradrive\Laradrive;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laradrive:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial setup for laradrive';

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
        $access_token = null;
        while($access_token == "" || $access_token == null){
            $access_token = $this->ask('Enter your Google OAuth2 Access Token');
        }

        $refresh_token = null;
        while($refresh_token == "" || $refresh_token == null){
            $refresh_token = $this->ask('Enter your Google OAuth2 Refresh Token');
        }
        
        File::replace(Laradrive::authTokenPath(), json_encode([
            "access_token" => $access_token,
            "refresh_token" => $refresh_token,
        ]));
        
        $this->info('Laradrive configured successfuly!');
    }
}
