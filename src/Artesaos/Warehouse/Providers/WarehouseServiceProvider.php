<?php namespace Artesaos\Warehouse\Providers;

use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../resources/config/warehouse.php' => config_path('warehouse.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../../resources/config/warehouse.php', 'warehouse'
        );

        $this->loadTranslationsFrom(__DIR__ . '/../../../resources/lang', 'warehouse');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}