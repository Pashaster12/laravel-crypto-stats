<?php

namespace LaravelCryptoStats\Connectors;

use Exception;
use GuzzleHttp\Client;
use LaravelCryptoStats\Services\CurrencyManager;

abstract class AbstractConnector
{
    use CurrencyManager;

    /**
     * Array of the cryptocurrencies supported
     * by the each of the API connector.
     *
     * @var array
     */
    public $supported_currencies;

    /**
     * Prefix for the API urls.
     *
     * @var string
     */
    protected $api_url_prefix;

    /**
     * Link to the API description.
     *
     * @var string
     */
    protected $api_description;

    /**
     * Prefix with the url of the corresponding block explorer
     * for creating block link.
     *
     * @var type
     */
    protected $block_link_prefix;

    /**
     * LaravelCryptoStats buider.
     *
     * Setting the $currency variable for accessing in the child classes
     */
    public function __construct()
    {
        $this->config = config('laravel_crypto_stats');
    }

    /**
     * Set the currency for the connector.
     *
     * @param string $currency
     *
     * @throws Exception
     */
    public function setCurrency(string $currency)
    {
        if ($currency) {
            $this->currency = $currency;
        } else {
            throw new Exception('Currency can not be empty!');
        }
    }

    /**
     * Validating if the input cryptocurrency address is a suitable address
     * for the $currency variable value.
     *
     * @param bool $address
     *
     * @return bool
     */
    abstract public function validateAddress(string $address): bool;

    /**
     * Get balance of the cryptocurrency wallet address.
     *
     * @param string $address
     *
     * @return float
     */
    abstract public function getBalance(string $address): float;

    /**
     * Get the wallet link to the corresponding block explorer (block link).
     *
     * @param string $address
     *
     * @return string
     */
    abstract public function getBlockExplorerLink(string $address): string;

    /**
     * Process the API response.
     *
     * @param string $url
     *
     * @return mixed
     */
    abstract protected function apiCall(string $url);

    /**
     * Wrappep of the Guzzle client for univarsal sending API calls
     * to all of the suitable cryptocurrency connectors.
     *
     * @param string $url
     *
     * @return mixed
     */
    protected function sendApiRequest(string $url)
    {
        if ($url) {
            $client = new Client();
            $response = $client->request('GET', $url);

            if ($response) {
                $response_body = json_decode($response->getBody(), true);

                return $response_body;
            }
        }

        throw new Exception('Something wrong with API '.$url.' call!');
    }

    /**
     * Round the balance value which was getted from the getBalance().
     *
     * @param type $balance
     *
     * @throws Exception
     *
     * @return float
     */
    protected function roundBalance($balance): float
    {
        if (isset($balance)) {
            return round($balance, 8);
        }

        throw new Exception('Balance can not be empty!');
    }
}
