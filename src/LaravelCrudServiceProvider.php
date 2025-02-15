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
        }
    }

    public function register()
    {
        // Register any bindings or services here
    }

}
