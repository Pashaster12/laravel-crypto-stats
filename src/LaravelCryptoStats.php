<?php

namespace LaravelCryptoStats;

use Exception;

class LaravelCryptoStats
{
    /**
     * Service config instance
     * 
     * @var array 
     */
    private $config;
    
    /**
     * Cryptocurrency of the created instance
     * 
     * @var string 
     */
    private $currency;
    
    /**
     * LaravelCryptoStats buider
     */
    public function __construct()
    {
        $this->config = config('laravel_crypto_stats');
    }
    
    /**
     * Return the list of the cryptocurrencies defined n the config
     * 
     * @return array
     */
    public function getCurrencies()
    {
        return $this->config['currencies'];
    }
    
    /**
     * Set the value of the $currency variable
     * 
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    /**
     * Dynamically call the method of the API connector instance
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!$this->currency)
            throw new Exception('Currency can not be null! Call setCurrency() for setting it.');

        $factory = new LaravelCryptoStatsFactory();
        $instance = $factory->getInstance($this->currency);

        if (!$instance) {
            throw new Exception('Instance of the LaravelCryptoStats API connector was not created!');
        }

        return $instance->$method(...$parameters);
    }
}
