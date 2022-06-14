<?php

namespace Dipeshsukhia\LaravelApiDevelop;

use Illuminate\Support\Str;

class LaravelApiDevelop
{
    const STUB_DIR = __DIR__ . '/resources/stubs/';
    protected $model;
    protected $result = false;

    public function __construct(string $model)
    {
        $this->model = $model;
        self::generate();
    }

    public function generate()
    {
        self::directoryCreate();
    }

    public function directoryCreate()
    {
        if (!file_exists(base_path('app/Http/Controllers/Api'))) {
            mkdir(base_path('app/Http/Controllers/Api'));
        }
        if (!file_exists(base_path('app/Http/Resources'))) {
            mkdir(base_path('app/Http/Resources'));
        }
    }

    /**
     * @return bool
     */
    public function generateController(): bool
    {
        $version = config('laravel-api-develop.version');
        if (!is_dir(app_path('Http/Controllers/Api/'.$version))) {
            mkdir(app_path('Http/Controllers/Api/'.$version));
        }
        $this->result = false;
        if (!file_exists(base_path('app/Http/Controllers/Api/' . $version . '/' . $this->model . 'Controller.php'))) {
            $template = self::getStubContents('ModelNameController.stub');
            $template = str_replace('{{version}}', $version, $template);
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{modelNameLower}}', strtolower($this->model), $template);
            $template = str_replace('{{modelNameCamel}}', Str::camel($this->model), $template);
            $template = str_replace('{{modelNameSpace}}', is_dir(base_path('app/Models')) ? 'Models\\' . $this->model : $this->model, $template);
            file_put_contents(base_path('app/Http/Controllers/Api/' . $version . '/' . $this->model . 'Controller.php'), $template);
            $this->result = true;
        }
        return $this->result;
    }

    /**
     * @return bool
     */
    public function generateResource(): bool
    {
        $this->result = false;
        if (!is_dir(app_path('Http/Resources/Resource'))) {
            mkdir(app_path('Http/Resources/Resource'));
        }
        if (!file_exists(app_path('Http/Resources/Resource/' . $this->model . 'Resource.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            $print_columns = null;
            foreach ($columns as $key => $column) {
                $print_columns .= "'" . $column . "'" . ' => $this->whenExists("' . $column . '"), ' . "\n \t\t\t";
            }
            $template = self::getStubContents('ModelNameResource.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Resources/Resource/' . $this->model . 'Resource.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    /**
     * @return bool
     */
    public function generateStoreRequest(): bool
    {
        $this->result = false;
        if (!is_dir(app_path('Http/Requests'))) {
            mkdir(app_path('Http/Requests'));
        }
        if (!file_exists(app_path('Http/Requests/Store' . $this->model . 'Request.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            $print_columns = null;
            foreach ($columns as $key => $column) {
                $print_columns .= "'" . $column . "'" . " => 'required', " . "\n \t\t\t";
            }
            $template = self::getStubContents('StoreModelNameRequest.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Requests/Store' . $this->model . 'Request.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    /**
     * @return bool
     */
    public function generateUpdateRequest(): bool
    {
        $this->result = false;
        if (!is_dir(app_path('Http/Requests'))) {
            mkdir(app_path('Http/Requests'));
        }
        if (!file_exists(app_path('Http/Requests/Update' . $this->model . 'Request.php'))) {
            $model = is_dir(base_path('app/Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);
            $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            $print_columns = null;
            foreach ($columns as $key => $column) {
                $print_columns .= "'" . $column . "'" . " => 'required', " . "\n \t\t\t";
            }
            $template = self::getStubContents('UpdateModelNameRequest.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            $template = str_replace('{{columns}}', $print_columns, $template);
            file_put_contents(base_path('app/Http/Requests/Update' . $this->model . 'Request.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    /**
     * @return bool
     */
    public function generateCollection(): bool
    {
        $this->result = false;
        if (!is_dir(app_path('Http/Resources/Collection'))) {
            mkdir(app_path('Http/Resources/Collection'));
        }
        if (!file_exists(base_path('app/Http/Resources/Collection/' . $this->model . 'Collection.php'))) {
            $template = self::getStubContents('ModelNameCollection.stub');
            $template = str_replace('{{modelName}}', $this->model, $template);
            file_put_contents(base_path('app/Http/Resources/Collection/' . $this->model . 'Collection.php'), $template);
            $this->result = true;
        }

        return $this->result;
    }

    /**
     * @return bool
     */
    public function generateRoute(): bool
    {
        $version = config('laravel-api-develop.version');
        $this->result = false;
        if (app()->version() >= 8) {
            $template = "\nuse App\Http\Controllers\Api\{{version}}\{{modelName}}Controller;";
            $nameSpace = str_replace('{{version}}', $version, $template);
            $template = "Route::apiResource('{{modelNameLower}}', {{modelName}}Controller::class);\n";
            $nameSpace = str_replace('{{modelName}}', $this->model, $nameSpace);
        } else {
            $template = "Route::apiResource('{{modelNameLower}}', 'Api\'.$version.'\{{modelName}}Controller');\n";
        }
        $route = str_replace('{{modelNameLower}}', Str::camel(Str::plural($this->model)), $template);
        $route = str_replace('{{modelName}}', $this->model, $route);
        if (!strpos(file_get_contents(base_path('routes/api.php')), $route)) {
            file_put_contents(base_path('routes/api.php'), $route, FILE_APPEND);
            if (app()->version() >= 8) {
                if (!strpos(file_get_contents(base_path('routes/api.php')), $nameSpace)) {
                    $lines = file(base_path('routes/api.php'));
                    $lines[0] = $lines[0] . "\n" . $nameSpace;
                    file_put_contents(base_path('routes/api.php'), $lines);
                }
            }
            $this->result = true;
        }

        return $this->result;
    }

    private function getStubContents($stubName)
    {
        return file_get_contents(self::STUB_DIR . $stubName);
    }
}
