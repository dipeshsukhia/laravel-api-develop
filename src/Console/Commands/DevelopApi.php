<?php

namespace Dipeshsukhia\LaravelApiDevelop\Console\Commands;

use Dipeshsukhia\LaravelApiDevelop\LaravelApiDevelop;
use Illuminate\Console\Command;

class DevelopApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develop-api {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Develop REST Api.';

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
        if (empty($this->option('model'))) {
            $this->error('Model Name Argument not found!');
            return false;
        }
        $model = is_dir(app_path('Models')) ? 'Models/' : '/';
        if (! file_exists(app_path($model.$this->option('model').'.php'))) {
            $this->error('Model does not exist!');
            return false;
        }
        $api = new LaravelApiDevelop($this->option('model'));
        $controller = $api->generateController();
        if ($controller) {
            $this->info('Controller Generated SuccessFully!');
        } else {
            $this->error('Controller Already Exists!');
        }

        $resource = $api->generateResource();
        if ($resource) {
            $this->info('Resource Generated SuccessFully!');
        } else {
            $this->error('Resource Already Exists!');
        }

        $resource = $api->generateStoreRequest();
        if ($resource) {
            $this->info('Store Request Generated SuccessFully!');
        } else {
            $this->error('Store Request Already Exists!');
        }

        $resource = $api->generateUpdateRequest();
        if ($resource) {
            $this->info('Update Request Generated SuccessFully!');
        } else {
            $this->error('Update Request Already Exists!');
        }

        $collection = $api->generateCollection();
        if ($collection) {
            $this->info('Collection Generated SuccessFully!');
        } else {
            $this->error('Collection Already Exists!');
        }

        $route = $api->generateRoute();
        if ($route) {
            $this->info('Route Generated SuccessFully!');
        } else {
            $this->error('Route Already Exists!');
        }

        $this->info('Api Created SuccessFully!');
        return true;
    }
}
