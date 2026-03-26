<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->register(\App\Providers\RegisterService\RegisterRoleService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom macro for created_at and updated_at as Epoch
        Blueprint::macro('epochTimestamps', function () {
            $this->unsignedBigInteger('created_at')->nullable();
            $this->unsignedBigInteger('updated_at')->nullable();
        });

        // Custom macro for deleted_at as Epoch
        Blueprint::macro('epochSoftDeletes', function () {
            $this->unsignedBigInteger('deleted_at')->nullable();
        });

        // Custom macro for user footprints
        Blueprint::macro('userFootprints', function () {
            $this->integer('created_by')->nullable();
            $this->integer('updated_by')->nullable();
            $this->integer('deleted_by')->nullable();
        });

        Blueprint::macro('uniqueSoftDelete', function ($column) {
            $table = $this->getTable();
            $indexName = "{$table}_{$column}_unique_active";

            return $this->addCommand('uniqueSoftDelete', compact('indexName', 'table', 'column'));
        });

        \Illuminate\Database\Schema\Grammars\PostgresGrammar::macro('compileUniqueSoftDelete', function (Blueprint $blueprint, Fluent $command) {
            return "CREATE UNIQUE INDEX {$command->indexName} ON {$command->table} ({$command->column}) WHERE deleted_at IS NULL";
        });
    }

    protected function registerService($serviceName, $className)
    {
        $this->app->singleton($serviceName, function () use ($className) {
            return new $className();
        });
    }
}