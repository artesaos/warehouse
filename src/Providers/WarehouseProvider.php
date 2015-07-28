<?php namespace Artesaos\Warehouse\Providers;

use Illuminate\Support\ServiceProvider;

class WarehouseProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../resources/config/warehouse.php' => config_path('warehouse.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/warehouse.php', 'warehouse'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Artesaos\Warehouse\Contracts\FractalFactory::class, \Artesaos\Warehouse\FractalFactory::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Artesaos\Warehouse\Contracts\FractalFactory'];
    }
}