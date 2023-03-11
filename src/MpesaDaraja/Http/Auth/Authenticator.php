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
    protected ?string $token = null;
    
    /**
     * Auth constructor.
     * @param CoreClient $coreClient
     */
    public function __construct(CoreClient $coreClient)
    {
        $this->coreClient = $coreClient;
    }
    
    /**
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function authenticate(string $app = 'default'): ?string
    {
        if ($this->getCachedToken($app)) {
            return $this->token;
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
            $this->token = $contents->access_token;
            return $this->token;
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
        $mpesaApp = $this->coreClient->config->get("mpesa.apps.$app");
        if (!$mpesaApp) {
            throw new ConfigurationException("You do not have such a Daraja App on your config file. Make sure $app app config is set and filled ");
        }
        $consumerKey = $this->coreClient->config->get("mpesa.apps.$app.consumer_key");
        $consumerSecret = $this->coreClient->config->get("mpesa.apps.$app.consumer_secret");
    
        if (!$consumerKey || !$consumerSecret) {
            throw new ConfigurationException("You have not set either consumer key or consumer secret for $app mpesa app");
        }
        return base64_encode($consumerKey . ':' . $consumerSecret);
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
        // $this->coreClient->cache->put("{$app}_mpesa_access_token", $contents->access_token, $expiry);
        cacheStore()->put("{$app}_mpesa_access_token", $contents->access_token, $expiry);
    }
    
    public function getCachedToken($app): bool
    {
        // $token = $this->coreClient->cache->get("{$app}_mpesa_access_token");
        $token = cacheStore()->get("{$app}_mpesa_access_token");
        $this->token = $token;
        return (bool) $token;
    }
    
}
