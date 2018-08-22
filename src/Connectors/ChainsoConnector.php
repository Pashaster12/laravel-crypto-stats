<?php

namespace LaravelCryptoStats\Connectors;

use Exception;

class ChainsoConnector extends AbstractConnector
{
    public $supported_currencies = ['BTC', 'LTC', 'DOGE', 'ZEC', 'DOGE'];    
    protected $api_url_prefix = 'https://chain.so//api/v2/';    
    protected $api_description = 'https://chain.so/api';    
    protected $block_link_prefix = 'https://chain.so/address/';
    
    public function validateAddress(string $address): bool
    {
        if ($address) {
            $url = $this->api_url_prefix . 'is_address_valid/' . $this->currency . '/' . $address;
            $response = $this->apiCall($url);

            if (isset($response['is_valid']))
                return $response['is_valid'];
        }

        throw new Exception('Wallet address can not be empty!');
    }
    
    public function getBalance(string $address): float
    {
        if ($address) {
            $url = $this->api_url_prefix . 'get_address_balance/' . $this->currency . '/' . $address;
            $response = $this->apiCall($url);

            if (isset($response['confirmed_balance']))
                return $this->roundBalance($response['confirmed_balance']);
        }

        throw new Exception('Wallet address can not be empty!');
    }
    
    public function getBlockExplorerLink(string $address): string
    {
        if($address) return $this->block_link_prefix . $this->currency . '/' . $address;
        
        throw new Exception('Wallet address can not be empty!');
    }
    
    protected function apiCall(string $url)
    {
        $response = $this->sendApiRequest($url);
        if ($response && isset($response['data']) && isset($response['status']) && $response['status'] == 'success') {
            return $response['data'];
        }

        throw new Exception('Output data is not correct. Check the API description - ' . $this->api_description . '!');
    }
}
