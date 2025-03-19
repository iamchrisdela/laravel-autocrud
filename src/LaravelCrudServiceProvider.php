<?php
namespace iamchris\LaravelCrud;

use Illuminate\Support\ServiceProvider;
use iamchris\LaravelCrud\Console\Commands\MakeCrudCommand;
class LaravelCrudServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCrudCommand::class,
            ]);
            $this->publishes([
                __DIR__ . '/../../../stubs' => base_path('stubs/vendor/laravel-autocrud'),
            ], 'laravel-autocrud-stubs');
        }
    }

    public function register()
    {
        // Register any bindings or services here
    }

}
