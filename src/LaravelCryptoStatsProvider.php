<?php

namespace LaravelCryptoStats;

use LaravelCryptoStats\Connectors\{ChainsoConnector, EtherscanConnector};
use Illuminate\Support\ServiceProvider;

class LaravelCryptoStatsProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel_crypto_stats.php' => config_path('laravel_crypto_stats.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('laravel-crypto-stats', function () {
            return new LaravelCryptoStats();
        });
        
        $this->app->bind('chainso', function () {
            return new ChainsoConnector();
        });
        
        $this->app->bind('etherscan', function () {
            return new EtherscanConnector();
        });
        
        $this->app->tag(['chainso', 'etherscan'], 'laravel-crypto-stats.connectors');
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-crypto-stats', 'chainso', 'etherscan'];
    }
}
