<?php
/**
 * Date 13/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\B2C;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class DispatchPayment extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/b2c/v1/paymentrequest';
    
    /*
     * Make a B2C Payment Request
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = formatParams($params);
    
        $initiator  = configStore()->get('mpesa.b2c.initiator_name');
        $initiatorPass = configStore()->get('mpesa.b2c.security_credential');
        $commandId  = configStore()->get('mpesa.b2c.default_command_id');
        $shortCode = configStore()->get('mpesa.b2c.short_code');
        $timeoutCallback  = configStore()->get('mpesa.b2c.timeout_url');
        $resultCallback  = configStore()->get('mpesa.b2c.result_url');
        // security cert
        $securityCert = configStore()->get('mpesa.b2c.security_cert');
        
        $configParams = [
            'InitiatorName' => $initiator,
            'SecurityCredential' => computeSecurityCredential($initiatorPass, $securityCert),
            'CommandID' => $commandId,
            'PartyA' => $shortCode,
            'QueueTimeOutURL' => $timeoutCallback,
            'ResultURL' => $resultCallback,
        ];
        
        // This gives precedence to params coming from user allowing them to override config params
        $body = array_merge($configParams, $userParams);
        $token = $this->bearer($app);
        $response = $this->coreClient->makeRequest(
            $this->endpoint,
            'POST',
            [
                'json' => $body,
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]
        );
        $contents = $response->getBody()->getContents();
        return json_decode($contents);
    }
}
