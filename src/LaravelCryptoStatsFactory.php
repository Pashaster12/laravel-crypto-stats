<?php

namespace LaravelCryptoStats;

use Exception;

class LaravelCryptoStatsFactory
{
    /**
     * Array of the existed API connectors.
     *
     * @var array
     */
    public $connectors;

    /**
     * LaravelCryptoStatsFactory buider.
     *
     * Get the array of the API connector classes and write it to the $connectors variable
     */
    public function __construct()
    {
        $connectors = app()->tagged('laravel-crypto-stats.connectors');
        $this->connectors = $connectors;
    }

    /**
     * Get the instance of the needed API connector class.
     *
     * @param string $currency - currency for which the API connector instance will be created
     *
     * @throws Exception
     *
     * @return \LaravelCryptoStats\connector - instance of the needed API connector class
     */
    public function getInstance($currency)
    {
        $connectors = $this->connectors;
        if ($connectors) {
            $supported_currencies = [];
            $instance = [];

            foreach ($connectors as $connector) {
                $connector->setCurrency($currency);
                $supported_currencies = array_merge($supported_currencies, $connector->supported_currencies);

                if (in_array($currency, $connector->supported_currencies)) {
                    $instance = $connector;
                }
            }

            if ($instance) {
                return $instance;
            }

            $supported_currencies = implode(', ', $supported_currencies);

            throw new Exception('"'.$currency.'" cryptocurrency is not supported now! Currently available values: '.$supported_currencies);
        }
    }
}
