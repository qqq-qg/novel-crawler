<?php

namespace Nac\Mg;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class MigrationsGenServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GeneratorCommand::class,
            function ($app) {
                return new GeneratorCommand(
                    $app->make('Nac\Mg\Generators\Generator'),
                    $app->make('Nac\Mg\Filesystem\Filesystem'),
                    $app->make('Nac\Mg\Compilers\TemplateCompiler'),
                    $app->make('migration.repository'),
                    $app->make('config')
                );
            }
        );
        $this->commands(GeneratorCommand::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }

    public function provides()
    {
        return [GeneratorCommand::class];
    }
}
