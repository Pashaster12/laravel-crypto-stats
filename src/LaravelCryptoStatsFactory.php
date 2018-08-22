<?php

namespace LaravelCryptoStats;

use LaravelCryptoStats\Services\ClassFinder;
use Exception;

class LaravelCryptoStatsFactory
{
    /**
     * Array of the existed API connectors
     * 
     * @var array 
     */
    public $connectors;
    
    /**
     * LaravelCryptoStatsFactory buider
     * 
     * Get the array of the API connector classes and write it to the $connectors variable
     */
    public function __construct()
    {
        $cf = new ClassFinder();
        $classes = $cf->getClassesInNamespace(__NAMESPACE__ . '\Connectors');
        
        $this->connectors = $classes;
    }
        
    /**
     * Get the instance of the needed API connector class
     * 
     * @param string $currency - currency for which the API connector instance will be created
     * @return \LaravelCryptoStats\connector - instance of the needed API connector class
     * @throws Exception
     */
    public function getInstance($currency)
    {
        $connectors = $this->connectors;
        if ($connectors) {
            $supported_currencies = [];
            $instance = [];

            foreach ($connectors as $connector) {
                $connector_instance = new $connector($currency);
                $supported_currencies = array_merge($supported_currencies, $connector_instance->supported_currencies);

                if (in_array($currency, $connector_instance->supported_currencies))
                    $instance = $connector_instance;
            }

            if ($instance)
                return $instance;

            $supported_currencies = implode(', ', $supported_currencies);

            throw new Exception('"' . $currency . '" cryptocurrency is not supported now! Currently available values: ' . $supported_currencies);
        }
    }
}
