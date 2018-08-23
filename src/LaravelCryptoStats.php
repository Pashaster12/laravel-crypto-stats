<?php

namespace LaravelCryptoStats;

use Exception;
use LaravelCryptoStats\Services\CurrencyManager;

class LaravelCryptoStats
{
    use CurrencyManager;

    /**
     * LaravelCryptoStats buider.
     */
    public function __construct()
    {
        $this->config = config('laravel_crypto_stats');
    }

    /**
     * Dynamically call the method of the API connector instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!$this->currency) {
            throw new Exception('Currency can not be null! Call setCurrency() for setting it.');
        }
        $factory = new LaravelCryptoStatsFactory();
        $instance = $factory->getInstance($this->currency);

        if (!$instance) {
            throw new Exception('Instance of the LaravelCryptoStats API connector was not created!');
        }

        return $instance->$method(...$parameters);
    }
}
