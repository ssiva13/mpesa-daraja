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
    
    protected array $validationRules = [
        'ReceiverParty' => 'required|numeric|min:5',
        'QueueTimeOutURL' => 'required|url',
        'ResultURL' => 'required|url',
        'Remarks' => 'required|string|max:20',
        'Occasion' => 'required|string|max:20',
        'CommandID' => 'required|string|max:64|exists_in:CommandID_reversal',
        'SecurityCredential' => 'required|string',
        'Initiator' => 'required|string',
        'TransactionID' => 'required|string',
        'Amount' => 'required|numeric|lte:max_txn|gte:min_txn',
        'RecieverIdentifierType' => 'required|numeric|exists_in:RecieverIdentifierType_accountbalance',
        'initiatorPass' => 'required',
        'securityCert' => 'required',
    ];
    
    /*
     * Reverse an M-Pesa Transaction
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = formatParams($params);
        
        [
            $shortCode, $resultCallback, $timeoutCallback, $initiator, $commandId, $initiatorPass, $securityCert,
            $identifierType
        ] = getConfigParams('reversal');
        
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
            'initiatorPass' => $initiatorPass,
            'securityCert' => $securityCert,
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