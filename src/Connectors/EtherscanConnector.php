<?php

namespace LaravelCryptoStats\Connectors;

use LaravelCryptoStats\Services\EthereumValidator;
use Exception;

class EtherscanConnector extends AbstractConnector
{
    public $supported_currencies = ['ETH'];    
    protected $api_url_prefix = 'https://api.etherscan.io/api?';    
    protected $api_description = 'https://etherscan.io/apis';    
    protected $block_link_prefix = 'https://etherscan.io/address/';
    
    public function validateAddress(string $address): bool
    {
        if($address) return EthereumValidator::isAddress($address);
        
        throw new Exception('Wallet address can not be empty!');
    }
    
    public function getBalance(string $address): float
    {
        if ($address) {
            $url = $this->api_url_prefix . 'module=account&action=balance&address=' . $address . '&tag=latest&apikey=' . $this->config['etherscan_api_key'];
            $response = $this->apiCall($url);

            if (is_numeric($response)) {
                $balance = $this->convertFromWei($response);
                return $this->roundBalance($balance);
            } else
                throw new Exception($response);
        }

        throw new Exception('Wallet address can not be empty!');
    }
    
    public function getBlockExplorerLink(string $address): string
    {
        if($address) return $this->block_link_prefix . $address;
        
        throw new Exception('Wallet address can not be empty!');
    }
    
    protected function apiCall(string $url)
    {
        $response = $this->sendApiRequest($url);
        if ($response && isset($response['status']) && isset($response['result'])) {
            return $response['result'];
        }

        throw new Exception('Output data is not correct. Check the API description - ' . $this->api_description . '!');
    }

    /**
     * Convert balance of the ETH wallet from the Wei points to the float
     * 
     * @param integer $balance
     * @return float
     */
    private function convertFromWei($balance): float
    {
        return $balance/pow(10, 18);
    }
}
