<?php
/**
 * Date 13/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\B2B;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class DispatchPayment extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/b2b/v1/paymentrequest';
    
    protected array $validationRules = [
        'PartyA' => 'required|numeric|min:5',
        'PartyB' => 'required|numeric|min:5',
        'QueueTimeOutURL' => 'required|url',
        'ResultURL' => 'required|url',
        'Remarks' => 'required|string|max:100',
        'CommandID' => 'required|string|max:30|exists_in:CommandID_b2b',
        'SenderIdentifierType' => 'required|numeric|exists_in:SenderIdentifierType_b2b',
        'RecieverIdentifierType' => 'required|numeric|exists_in:RecieverIdentifierType_b2b',
        'SecurityCredential' => 'required|string',
        'Initiator' => 'required|string',
        'AccountReference' => 'required|string|max:12',
        'initiatorPass' => 'required',
        'securityCert' => 'required',
        'Amount' => 'required|numeric|lte:max_txn|gte:min_txn',
    ];
    
    /*
     * Make a B2B Payment Request
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = formatParams($params);
    
        $initiator  = configStore()->get('mpesa.b2b.initiator_name');
        $initiatorPass = configStore()->get('mpesa.b2b.security_credential');
        $commandId  = configStore()->get('mpesa.b2b.default_command_id');
        $senderIdentifier  = configStore()->get('mpesa.b2b.sender_identifier');
        $receiverIdentifier  = configStore()->get('mpesa.b2b.receiver_identifier');
        $shortCode = configStore()->get('mpesa.b2c.short_code');
        $timeoutCallback  = configStore()->get('mpesa.b2b.timeout_url');
        $resultCallback  = configStore()->get('mpesa.b2b.result_url');
        // security cert
        $securityCert = configStore()->get('mpesa.b2b.security_cert');
        
        $configParams = [
            'Initiator' => $initiator,
            'SecurityCredential' => computeSecurityCredential($initiatorPass, $securityCert),
            'CommandID' => $commandId,
            'SenderIdentifierType' => $senderIdentifier,
            'RecieverIdentifierType' => $receiverIdentifier,
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
