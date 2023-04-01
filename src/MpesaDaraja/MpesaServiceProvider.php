<?php

namespace Ssiva\MpesaDaraja;

use Illuminate\Support\ServiceProvider;

/**
 * Date 01/04/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */
class MpesaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mpesa.php' => config_path('mpesa.php')
        ], 'mpesa_config');
    }
    
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('Ssiva\MpesaDaraja\Mpesa', function ($app) {
            $config = $app['config']->get('mpesa');
            return new Mpesa($config);
        });
    }
}