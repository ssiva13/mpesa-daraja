<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Ssiva\MpesaDaraja\Contracts\CacheStore;
use Ssiva\MpesaDaraja\Contracts\ConfigurationStore;
use Ssiva\MpesaDaraja\Exceptions\ConfigurationException;
use Ssiva\MpesaDaraja\Exceptions\MpesaGuzzleException;
use Ssiva\MpesaDaraja\Http\Auth\Authenticator;
use Ssiva\MpesaDaraja\Validation\Validator;

class CoreClient
{
    public ConfigurationStore $config;
    public CacheStore $cache;
    public string $baseUrl;
    public array $validationRules;
    public Client $httpClient;

    public Validator $validator;
    public Authenticator $auth;


    public function __construct(ConfigurationStore $configStore, $cacheStore)
    {
        $this->config = $configStore;
        $this->cache = $cacheStore;
        $this->validator = new Validator();
        $this->setBaseUrl();
        $this->setAuthenticator();
        $this->httpClient = $this->setCoreClient();
        // $this->auth = $auth;

    }

    /**
     * Set authenticator to be used to get token
     *
     * @return void
     * @throws \ReflectionException
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
     *
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    private function setBaseUrl(): void
    {
        if(!$apiRoot = $this->config->get('mpesa.api_url', '')){
            $this->throwConfigException('MPESA_URL is not set!');
        }
        if (!str_ends_with($apiRoot, '/')) {
            $apiRoot = $apiRoot . '/';
        }
        $this->baseUrl = $apiRoot;
    }

    /**
     * @throws \ReflectionException
     */
    public function setValidationRules($rules): void
    {
        $this->validationRules = $rules;
        foreach ($this->validationRules as $param => $rule) {
            $this->validator->add($param, $rule);
        }
    }

    /**
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    private function validateRequestBodyParams($params): void
    {
        if (!$this->validator->validate($params)) {
            $errors = $this->validator->getMessages();
            $labeledErrors = $this->validator->getLabeledMessages();
            $finalErrors = [];
            foreach($errors as $errKey => $err){
                foreach($err as $key => $er){
                    $finalErrors[] = $labeledErrors[$errKey][$key];
                    // $finalErrors['labeled'][] = $labeledErrors[$errKey][$key];
                    // $finalErrors['plain'][] = $er;
                }
            }
            $this->throwConfigException(\json_encode($finalErrors));
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function makeRequest($uri, $method, $options = []): ResponseInterface
    {
        if (!empty($this->validationRules) && isset($options['json'])) {
            $this->validateRequestBodyParams($options['json']);
        }

        try {
            switch ($method){
                case 'POST':
                    return $this->httpClient->post($uri, $options);
                default:
                    return $this->httpClient->get($uri, $options);
            }
        }
        catch (ClientException|ServerException $exception) {
            return (new MpesaGuzzleException())->generateException($exception);
        }
    }

    /**
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function throwConfigException($reason){
        throw new ConfigurationException($reason,422);
    }
}
