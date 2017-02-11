<?php

namespace UIS\Mvf\Laravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use UIS\Mvf\ValidationManager;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../../localization/en' => resource_path('lang/en'),
        ], 'lang');

        ValidationManager::setTransFunction('trans');
    }
}
