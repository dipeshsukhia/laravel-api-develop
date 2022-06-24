<?php

namespace Dipeshsukhia\LaravelApiDevelop;

use Illuminate\Support\Str;

class LaravelApiDevelop
{
    const STUB_DIR = __DIR__ . '/resources/stubs/';
    protected $model;
    protected $result = false;
    protected $version = '';

    public function __construct(string $model)
    {
        $this->model = $model;
        self::generate();
    }

    public function generate()
    {
        $this->version = Str::ucfirst(Str::camel(config('laravel-api-develop.version')));
        self::directoryCreate();        
    }

    public function directoryCreate()
    {
        if (!file_exists(app_path('Http/Controllers/Api'))) {
            mkdir(app_path('Http/Controllers/Api'));
        }
        if (!is_dir(app_path('Http/Controllers/Api/' . $this->version))) {
            mkdir(app_path('Http/Controllers/Api/' . $this->version));
        }
        if (!file_exists(app_path('Http/Resources'))) {
            mkdir(app_path('Http/Resources'));
        }
        if (!is_dir(app_path('Http/Resources/Resource'))) {
            mkdir(app_path('Http/Resources/Resource'));
        }
        if (!is_dir(app_path('Http/Resources/Collection'))) {
            mkdir(app_path('Http/Resources/Collection'));
        }
        if (!is_dir(app_path('Http/Requests'))) {
            mkdir(app_path('Http/Requests'));
        }
    }

    /**
     * @return bool
     */
    public function generateController(): bool
    {
        $this->result = false;
        if (!file_exists(app_path('Http/Controllers/Api/' . $this->version . '/' . $this->model . 'Controller.php'))) {
            $template = str_replace([
                '{{version}}',
                '{{modelName}}',
                '{{modelNameLower}}',
                '{{modelNameCamel}}',
                '{{modelNameSpace}}'
            ], [
                $this->version,
                $this->model,
                strtolower($this->model),
                Str::camel($this->model),
                is_dir(app_path('Models')) ? 'Models\\' . $this->model : $this->model
            ], self::getStubContents('ModelNameController.stub'));
            file_put_contents(app_path('Http/Controllers/Api/' . $this->version . '/' . $this->model . 'Controller.php'), $template);
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
        if (!file_exists(app_path('Http/Resources/Resource/' . $this->model . 'Resource.php'))) {
            $model = is_dir(app_path('Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);

            $print_columns = implode("\n \t\t\t", array_map(function ($column) {
                return "'" . $column . "'" . ' => $this->whenExists("' . $column . '"), ';
            }, $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable())));

            $template = str_replace([
                '{{modelName}}',
                '{{columns}}'
            ], [
                $this->model,
                $print_columns
            ], self::getStubContents('ModelNameResource.stub'));
            file_put_contents(app_path('Http/Resources/Resource/' . $this->model . 'Resource.php'), $template);
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
        if (!file_exists(app_path('Http/Requests/Store' . $this->model . 'Request.php'))) {
            $model = is_dir(app_path('Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);

            $print_columns = implode("\n \t\t\t", array_map(function ($column) {
                return "'" . $column . "'" . " => 'required', ";
            }, $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable())));

            $template = str_replace([
                '{{modelName}}',
                '{{columns}}'
            ], [
                $this->model,
                $print_columns
            ], self::getStubContents('StoreModelNameRequest.stub'));
            file_put_contents(app_path('Http/Requests/Store' . $this->model . 'Request.php'), $template);
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
        if (!file_exists(app_path('Http/Requests/Update' . $this->model . 'Request.php'))) {
            $model = is_dir(app_path('Models')) ? app('App\\Models\\' . $this->model) : app('App\\' . $this->model);

            $print_columns = implode("\n \t\t\t", array_map(function ($column) {
                return "'" . $column . "'" . " => 'required', ";
            }, $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable())));

            $template = str_replace([
                '{{modelName}}',
                '{{columns}}'
            ], [
                $this->model,
                $print_columns
            ], self::getStubContents('UpdateModelNameRequest.stub'));
            file_put_contents(app_path('Http/Requests/Update' . $this->model . 'Request.php'), $template);
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
        if (!file_exists(app_path('Http/Resources/Collection/' . $this->model . 'Collection.php'))) {
            $template = str_replace('{{modelName}}', $this->model, self::getStubContents('ModelNameCollection.stub'));
            file_put_contents(app_path('Http/Resources/Collection/' . $this->model . 'Collection.php'), $template);
            $this->result = true;
        }
        return $this->result;
    }

    /**
     * @return bool
     */
    public function generateRoute(): bool
    {
        $this->result = false;
        if (app()->version() >= 8) {
            $nameSpaceTemplate = "use App\Http\Controllers\Api\{{version}}\{{modelName}}Controller;";
            $nameSpace = str_replace([
                '{{version}}',
                '{{modelName}}'
            ], [
                $this->version,
                $this->model
            ], $nameSpaceTemplate);
            $routeTemplate = "Route::apiResource('{{modelNameLower}}', {{modelName}}Controller::class);\n";
        } else {
            $routeTemplate = "Route::apiResource('{{modelNameLower}}', 'Api\'.$this->version.'\{{modelName}}Controller');\n";
        }
        $route = str_replace([
            '{{modelNameLower}}',
            '{{modelName}}'
        ], [
            Str::camel(Str::plural($this->model)),
            $this->model
        ], $routeTemplate);

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
