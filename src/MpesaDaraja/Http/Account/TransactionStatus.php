<?php
/**
 * Date 12/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Account;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class TransactionStatus extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/transactionstatus/v1/query';
    
    protected array $validationRules = [
        'QueueTimeOutURL' => 'required|url',
        'ResultURL' => 'required|url',
        'CommandID' => 'required|string|max:64|exists_in:CommandID_transactionstatus',
        'SecurityCredential' => 'required|string',
        'Initiator' => 'required|string',
        'initiatorPass' => 'required',
        'securityCert' => 'required',
        'IdentifierType' => 'required|numeric|exists_in:IdentifierType_transactionstatus',
        'PartyA' => 'required|numeric|min:5',
        'Remarks' => 'required|string|max:20',
        'TransactionID' => 'required|string',
        'Occasion' => 'required|string|max:20',
    ];
    
    /*
     * Query the Transaction Status of an M-Pesa Transaction
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = formatParams($params);
        [
            $shortCode, $resultCallback, $timeoutCallback, $initiator, $commandId, $initiatorPass, $securityCert,
            $identifierType
        ] = getConfigParams('transaction');
        
        $configParams = [
            'Initiator' => $initiator,
            'SecurityCredential' => computeSecurityCredential($initiatorPass, $securityCert),
            'IdentifierType' => $identifierType,
            'CommandID' => $commandId,
            'PartyA' => $shortCode,
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
