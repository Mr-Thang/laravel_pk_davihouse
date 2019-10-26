<?php

namespace CMS\Package\Providers;

use Illuminate\Support\ServiceProvider;

class CMSPackageServiceProvider extends ServiceProvider
{
    /**
     *
     *
     * @return  void  
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . './../resources/config/repository.php' => config_path('repository.php')
        ]);
        $this->mergeConfigFrom(__DIR__ . './../resources/config/repository.php', 'repository');
    }

    /**
     * Register the service provider
     *
     * @return  void
     */
    public function register()
    {
        # code...
    }
}
