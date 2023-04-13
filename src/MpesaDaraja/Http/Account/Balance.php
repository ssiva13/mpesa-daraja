<?php
/**
 * Date 12/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Account;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class Balance extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/accountbalance/v1/query';
    
    protected array $validationRules = [
        'PartyA' => 'required|numeric|min:4',
        'QueueTimeOutURL' => 'required|url',
        'ResultURL' => 'required|url',
        'Remarks' => 'required|string|max:100',
        'CommandID' => 'required|string|max:64|exists_in:CommandID_accountbalance',
        'SecurityCredential' => 'required|string',
        'Initiator' => 'required|string',
        'IdentifierType' => 'required|numeric|exists_in:IdentifierType_accountbalance',
        'initiatorPass' => 'required',
        'securityCert' => 'required',
    ];
    
    /*
     * Make an Account Balance query
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = formatParams($params);
        
        [
            $shortCode, $resultCallback, $timeoutCallback, $initiator, $commandId, $initiatorPass, $securityCert,
            $identifierType
        ] = getConfigParams('balance');
        
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