<?php
/**
 * Date 12/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Account;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class Reversal extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/reversal/v1/request';
    
    /*
     * Reverse an M-Pesa Transaction
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = formatParams($params);
        
        $shortCode = configStore()->get('mpesa.account.short_code');
        $resultCallback  = configStore()->get('mpesa.account.result_url');
        $timeoutCallback  = configStore()->get('mpesa.account.timeout_url');
        $initiator  = configStore()->get('mpesa.account.initiator_name');
        $commandId  = configStore()->get('mpesa.account.reversal.default_command_id');
        $initiatorPass = configStore()->get('mpesa.account.security_credential');
        $securityCert = configStore()->get('mpesa.account.security_cert');
        $identifierType = configStore()->get('mpesa.account.identifier_type');
        
        $configParams = [
            'Initiator' => $initiator,
            'SecurityCredential' => computeSecurityCredential($initiatorPass, $securityCert),
            'RecieverIdentifierType' => $identifierType,
            // 'IdentifierType' => $identifierType,
            'CommandID' => $commandId,
            'ReceiverParty' => $shortCode,
            // 'PartyA' => $shortCode,
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