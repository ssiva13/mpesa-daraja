<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Auth;

use Exception;
use Ssiva\MpesaDaraja\Exceptions\AuthException;
use Ssiva\MpesaDaraja\Exceptions\ConfigurationException;
use Ssiva\MpesaDaraja\Http\CoreClient;

class Authenticator
{
    protected string $endpoint = 'oauth/v1/generate';
    protected CoreClient $coreClient;
    
    public function setEngine(CoreClient $coreClient)
    {
        $this->coreClient = $coreClient;
    }
    
    /**
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function authenticate(string $app = 'default')
    {
        if ($token = $this->coreClient->cache->get("{$app}_mpesa_access_token")) {
            return $token;
        }
        try {
            $credentials = $this->generateCredentials($app);
            
            $response = $this->coreClient->makeRequest(
                'GET',
                $this->endpoint,
                [
                    'query' => [
                        'grant_type' => 'client_credentials'
                    ],
                    'headers' => [
                        'Authorization' => 'Basic ' . $credentials
                    ]
                ]
            );
            
            $contents = json_decode($response->getBody()->getContents());
            $this->storeAuthCredentials($contents, $app);
    
            if (!empty($response->errorCode)) {
                throw new Exception(json_encode($response));
            }
            
            return $contents->access_token;
            
        }
        catch (AuthException $exception) {
            throw $exception->generateException();
        }
    }
    
    /**
     * @param string $app
     *
     * @return string
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    private function generateCredentials(string $app = 'default'): string
    {
        $allDarajaAps = $this->coreClient->config->get('mpesa.apps');
        if (!isset($allDarajaAps[$app])) {
            throw new ConfigurationException("You do not have such a Daraja App on your config file. Make sure $app is set and filled ");
        }
        $secret = $this->coreClient->config->get('mpesa.apps.consumer_key');
        $key = $this->coreClient->config->get('mpesa.apps.consumer_secret');
        if (empty($key) || empty($secret)) {
            throw new ConfigurationException("You have not set either consumer key or consumer secret for $app app");
        }
        return base64_encode($key . ':' . $secret);
    }
    
    /**
     * @param string $app
     * @param $contents
     *
     * @return void
     */
    public function storeAuthCredentials($contents, string $app): void
    {
        $expiry = addInterval(0.9 * $contents->expires_in);
        $this->coreClient->cache->put("{$app}_mpesa_access_token", $contents->access_token, $expiry);
        cache()->put("{$app}_mpesa_access_token", $contents->access_token, $expiry);
    }
    
}
