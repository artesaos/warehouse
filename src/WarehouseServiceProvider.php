<?php

namespace Artesaos\Warehouse;

use Artesaos\Warehouse\Contracts\Fractal\Factory as FractalFactoryContract;
use Artesaos\Warehouse\Fractal\Factory as FractalFactory;
use Illuminate\Support\ServiceProvider;

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
        $resourcesDir = __DIR__.'/../resources/';
        $this->publishes([$resourcesDir.'config/warehouse.php' => config_path('warehouse.php')]);

        $this->mergeConfigFrom($resourcesDir.'config/warehouse.php', 'warehouse');
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
