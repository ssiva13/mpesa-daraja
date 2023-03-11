<?php
/**
 * Date 01/03/2023
 *
 * Generate an OAuth Access Token
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\MpesaOnline;

use Exception;
use Ssiva\MpesaDaraja\Exceptions\AuthException;
use Ssiva\MpesaDaraja\Http\CoreClient;

class STKPush
{
    protected string $endpoint = 'mpesa/stkpush/v1/processrequest';
    protected CoreClient $coreClient;
    
    /**
     * Auth constructor.
     * @param CoreClient $coreClient
     */
    public function __construct(CoreClient $coreClient)
    {
        $this->coreClient = $coreClient;
    }
    
    /**
     * @throws \Ssiva\MpesaDaraja\Exceptions\ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function push($params = [], string $app = 'default'): ?string
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = [];
        foreach ($params as $key => $value) {
            $userParams[ucwords($key)] = $value;
        }
        $time = now('YmdHis');
        $shortCode = configStore()->get('mpesa.mpesa_online.short_code');
        $passkey = configStore()->get('mpesa.mpesa_online.passkey');
        $password = \base64_encode($shortCode.$passkey.$time);
        // Computed and params from config file.
        $configParams = [
            'BusinessShortCode' => $shortCode,
            'CallBackURL' => configStore()->get('mpesa.mpesa_online.callback'),
            'TransactionType' => configStore()->get('mpesa.mpesa_online.default_transaction_type'),
            'Password' => $password,
            'PartyB' => $shortCode,
            'Timestamp' => $time,
        ];
        // This gives precedence to params coming from user allowing them to override config params
        $body = array_merge($configParams, $userParams);
        if (empty($body['PartyA']) && !empty($body['PhoneNumber'])) {
            $body['PartyA'] = $body['PhoneNumber'];
        }
        
        try {
            
            $response = $this->coreClient->makeRequest(
                $this->endpoint,
                'POST',
                [
                    'body' => $body,
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->coreClient->auth->authenticate($app)
                    ]
                ]
            );
            
            $contents = json_decode($response->getBody()->getContents());
            
            if (!empty($response->errorCode)) {
                throw new Exception(json_encode($response));
            }
            return $contents;
        }
        catch (AuthException $exception) {
            throw $exception->generateException();
        }
    }
    
}
