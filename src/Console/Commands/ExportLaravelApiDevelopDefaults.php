<?php

namespace Dipeshsukhia\LaravelApiDevelop\Console\Commands;

use Illuminate\Console\Command;

class ExportLaravelApiDevelopDefaults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develop-api:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install develop-api defaults';

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
     * @return bool
     */
    public function handle(): bool
    {
        $this->info('Exporting files ...');

        $this->info('Publishing Configuration...');
        $this->publishConfiguration(false);
        $this->info('Files Exported');
        return true;
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Dipeshsukhia\LaravelApiDevelop\LaravelApiDevelopServiceProvider",
            '--tag' => "LaravelApiDevelop"
        ];
        if ($forcePublish === true) {
            $params['--force'] = '';
        }
        $this->call('vendor:publish', $params);
    }
}
