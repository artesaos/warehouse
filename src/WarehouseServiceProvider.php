<?php

namespace Artesaos\Warehouse;

use Illuminate\Support\ServiceProvider;
use Artesaos\Warehouse\Fractal\FractalFactory;
use Artesaos\Warehouse\Contracts\FractalFactory as FractalFactoryContract;

class WarehouseServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        $resourcesDir = __DIR__ . '/../resources/';
        $this->publishes([$resourcesDir . 'config/warehouse.php' => config_path('warehouse.php')]);

        $this->mergeConfigFrom($resourcesDir . 'config/warehouse.php', 'warehouse');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FractalFactoryContract::class, FractalFactory::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [FractalFactoryContract::class];
    }
}