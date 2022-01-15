<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

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
        $this->call('key:generate'); 
        $this->call('passport:keys'); 
        $this->call('migrate:refresh');
        $this->call('db:seed');
        $this->call('passport:client', [
            '--personal' => 'custom_token',
            '--name'=> 'custom_token',
            '--provider'=> 'users'
        ]);
        $this->call('passport:client', [
            '--password' => 'password_token',
            '--name'=> 'password_token',
            '--provider'=> 'users',
            '--user_id'=> 1
        ]);
        $this->call('route:cache');
        $this->call('scribe:generate');
        return Command::SUCCESS;
    }
}
