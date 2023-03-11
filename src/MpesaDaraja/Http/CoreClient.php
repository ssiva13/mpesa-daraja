<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Ssiva\MpesaDaraja\Contracts\CacheStore;
use Ssiva\MpesaDaraja\Contracts\ConfigurationStore;
use Ssiva\MpesaDaraja\Http\Auth\Authenticator;

class CoreClient
{
    public ConfigurationStore $config;
    public CacheStore $cache;
    public string $baseUrl;
    public array $validationRules;
    public Client $httpClient;
    
    public $validator;
    public Authenticator $auth;
    
    
    public function __construct(ConfigurationStore $configStore, $cacheStore)
    {
        $this->config = $configStore;
        $this->cache = $cacheStore;
        $this->setBaseUrl();
        $this->setAuthenticator();
        $this->httpClient = $this->setCoreClient();
        // $this->validator = new Validator();
        // $this->auth = $auth;
        
    }
    
    /**
     * Set authenticator to be used to get token
     * @return void
     */
    public function setAuthenticator(){
        $this->auth = new Authenticator($this);
    }
    
    public function setCoreClient(): Client
    {
        return new Client([
            'base_uri' => $this->baseUrl,
            'verify' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }
    
    /**
     * Validate the current package state.
     */
    private function setBaseUrl()
    {
        $apiRoot = $this->config->get('mpesa.api_url', '');
        if (substr($apiRoot, strlen($apiRoot) - 1) !== '/') {
            $apiRoot = $apiRoot . '/';
        }
        $this->baseUrl = $apiRoot;
    }
    
    public function setValidationRules($rules)
    {
        $this->validationRules = $rules;
        foreach ($this->validationRules as $key => $value) {
            $this->validator->add($key, $value);
        }
    }
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeRequest($uri, $method, $options = []): ResponseInterface
    {
        switch ($method){
            case 'POST':
                return $this->httpClient->post($uri, $options);
            default:
                return $this->httpClient->get($uri, $options);
        }
    }
}
