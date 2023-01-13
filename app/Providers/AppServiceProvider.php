<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Interfaces\PostRepositoryInterface',
            'App\Repositories\PostRepository',
            'App\Sercices\ExternalApiClient',
            'App\Sercices\UtilsService',
            'App\Sercices\ProcessService',
            
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
