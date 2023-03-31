<?php

/**
 * Date 14/03/2023
 *
 * @author   kelvin mukotso <kelvinmukotso@gmail.com>
 */


namespace Ssiva\MpesaDaraja\Http\C2B;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class ProcessCustomerPayment extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/c2b/v1/simulate';

    protected array $validationRules = [
        'ShortCode' => 'required|numeric|min:5',
        'CommandID' => 'required|string|max:30|exists_in:CommandID_c2b',
        'Msisdn' => 'required|numeric|phone',
        'Amount' => 'required|numeric|lte:max_txn|gte:min_txn',
        'BillRefNumber' => 'required_if:CommandID_c2b',
    ];
    
    /*
     * Make a C2B Payment Request
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // converts the first character of each indexes in a string to uppercase
        //user Params to include (Amount ,phone number initiating the C2B transaction (Msisdn)
        // , BillRefNumber  (Account Number N/B for PayBill Only ), ShortCode(optional)
        $userParams = formatParams($params);

        $commandId = configStore()->get('mpesa.c2b.default_command_id');
        $shortCode = configStore()->get('mpesa.c2b.short_code');

        $configParams = [
            'CommandID' => $commandId,
            'ShortCode' => $shortCode,
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
