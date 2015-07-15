<?php namespace Artesaos\Warehouse\Providers;

use Illuminate\Support\ServiceProvider;

class WarehouseProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Artesaos\Warehouse\Contracts\FractalFactory', 'Artesaos\Warehouse\FractalFactory');
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