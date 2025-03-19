<?php

namespace iamchris\LaravelCrud\Console\Commands;;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use ReflectionClass;
class MakeCrudCommand extends Command
{
    protected $signature = 'make:crud {model : The name of the model}';
    protected $description = 'Generate CRUD scaffolding for a model like Symfony\'s make:crud.';

    public function handle()
    {
        $modelName = $this->argument('model');
        $modelClass = "App\\Models\\$modelName";

        // Check if the model exists
        if (!class_exists($modelClass)) {
            $this->error("Model $modelClass does not exist.");
            return;
        }

        // Get the model's fields
        $fields = $this->getModelFields($modelClass);

        // Generate Controller
        $this->generateController($modelName, $fields);

        // Generate Views
        $this->generateViews($modelName, $fields);

        // Generate Routes
        $this->appendRoutes($modelName);

        $this->info("CRUD scaffolding for $modelName generated successfully!");
    }

    protected function getModelFields($modelClass)
    {
        $model = new $modelClass;
        $fillable = $model->getFillable();

        if (empty($fillable)) {
            $this->error("No fillable fields found in the model.");
            return [];
        }

        return $fillable;
    }

    protected function generateController($modelName, $fields)
    {
        $controllerName = "{$modelName}Controller";
        $controllerPath = app_path("Http/Controllers/{$controllerName}.php");

        // Check if the controller already exists
        if (file_exists($controllerPath)) {
            if (!$this->confirm("Controller $controllerName already exists. Overwrite it?")) {
                $this->info("Skipping controller creation.");
                return;
            }
        }

        // Generate the controller
        $stub = $this->getControllerStub($modelName, $fields);
        file_put_contents($controllerPath, $stub);

        $this->info("Controller created: $controllerName");
    }

    protected function getControllerStub($modelName, $fields)
    {
        //$stubPath = __DIR__ . '/../../../stubs/controller.stub';
        $stubPath = base_path('stubs/vendor/laravel-autocrud/controller.stub');
        if (!file_exists($stubPath)) {
            $this->error("Stub file not found: $stubPath");
            return;
        }

        $stub = file_get_contents($stubPath);
        $stub = str_replace('{{modelName}}', $modelName, $stub);
        $stub = str_replace('{{modelVariable}}', Str::camel($modelName), $stub);
        $stub = str_replace('{{fields}}', $this->getFieldsForValidation($fields), $stub);

        return $stub;
    }

    protected function getFieldsForValidation($fields)
    {
        $rules = [];
        foreach ($fields as $field) {
            $rules[] = "'$field' => 'required'";
        }
        return implode(",\n            ", $rules);
    }

    protected function generateViews($modelName, $fields)
    {
        $viewsPath = resource_path("views/" . Str::kebab($modelName));
        if (!file_exists($viewsPath)) {
            mkdir($viewsPath, 0777, true);
        }

        $views = ['index', 'create', 'edit', 'show'];
        foreach ($views as $view) {
            $viewPath = "$viewsPath/$view.blade.php";

            // Check if the view already exists
            if (file_exists($viewPath)) {
                if (!$this->confirm("View $view.blade.php already exists. Overwrite it?")) {
                    $this->info("Skipping view creation for $view.blade.php.");
                    continue;
                }
            }

            // Generate the view
            $stub = $this->getViewStub($view, $modelName, $fields);
            file_put_contents($viewPath, $stub);
        }

        $this->info("Views created in: $viewsPath");
    }

    protected function getViewStub($view, $modelName, $fields)
    {
        //$stubPath = __DIR__ . '/../../../stubs/' . $view . '.stub';
        $stubPath = base_path('stubs/vendor/laravel-autocrud/' . $view . '.stub'); // Published path
        if (!file_exists($stubPath)) {
            $this->error("Stub file not found: $stubPath");
            return;
        }

        $stub = file_get_contents($stubPath);
        $stub = str_replace('{{modelName}}', $modelName, $stub);
        $stub = str_replace('{{modelVariable}}', Str::camel($modelName), $stub);
        $stub = str_replace('{{fields}}', $this->getFieldsForForm($fields), $stub);

        return $stub;
    }

    protected function getFieldsForForm($fields)
    {
        $formFields = [];
        foreach ($fields as $field) {
            $formFields[] = <<<EOT
        <div class="form-group">
            <label for="$field">$field</label>
            <input type="text" name="$field" id="$field" class="form-control" value="{{ old('$field') }}">
        </div>
EOT;
        }
        return implode("\n", $formFields);
    }

    protected function appendRoutes($modelName)
    {
        $routeContent = "Route::resource('" . Str::kebab($modelName) . "', " . $modelName . "Controller::class);";
        $routesFilePath = base_path('routes/web.php');

        // Read the existing routes file
        $existingRoutes = file_get_contents($routesFilePath);

        // Check if the route already exists
        if (str_contains($existingRoutes, $routeContent)) {
            $this->info("Route for $modelName already exists. Skipping route creation.");
            return;
        }

        // Append the route to the web.php file
        file_put_contents($routesFilePath, PHP_EOL . $routeContent, FILE_APPEND);

        $this->info("Routes added for $modelName");
    }
//    protected function appendRoutes($modelName)
//    {
//        $routeContent = <<<EOT
//        Route::resource('" . Str::kebab($modelName) . "', " . $modelName . "Controller::class);
//        EOT;
//
//        // Corrected version:
//        $routeContent = "Route::resource('" . Str::kebab($modelName) . "', " . $modelName . "Controller::class);";
//
//        // Append the route to the web.php file
//        file_put_contents(base_path('routes/web.php'), PHP_EOL . $routeContent, FILE_APPEND);
//
//        $this->info("Routes added for $modelName");
//    }
}
