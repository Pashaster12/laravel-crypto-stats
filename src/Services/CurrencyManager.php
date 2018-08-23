<?php

namespace LaravelCryptoStats\Services;

trait CurrencyManager
{
    /**
     * Service config instance
     * 
     * @var array 
     */
    protected $config;
    
    /**
     * Cryptocurrency of the created instance
     * 
     * @var string 
     */
    protected $currency;
    
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
}