<?php
/**
 * Date 01/03/2023
 *
 * Initiate a Lipa na M-Pesa Online Payment
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\MpesaOnline;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class STKPush extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/stkpush/v1/processrequest';

    protected array $validationRules = [
        'TransactionType' => 'required|string|exists_in:TransactionType_stkpush',
        'Amount' => 'required|numeric|lte:max_txn|gte:min_txn',
        'PartyA' => 'required|numeric|phone',
        'PhoneNumber' => 'required|numeric|phone',
        'PartyB' => 'required|numeric|min:6',
        'AccountReference' => 'required|string|max:12',
        'TransactionDesc' => 'string|max:13|min:1',
        'CallBackURL' => 'required|url',
        'Password' => 'required|string',
        'PassKey' => 'required|string',
        'Timestamp' => 'required|datetime',
        'BusinessShortCode' => 'required|numeric|min:5',
    ];

    /**
     * @param array $params
     * @param string $app
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     * @throws \Exception
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // Make sure all the indexes are in Uppercases as shown in docs
        $userParams = formatParams($params);

        $time = now('YmdHis');
        $shortCode = configStore()->get('mpesa.mpesa_online.short_code');
        $passkey = configStore()->get('mpesa.mpesa_online.passkey');
        $password = base64_encode($shortCode.$passkey.$time);
        // Computed and params from config file.
        $configParams = [
            'BusinessShortCode' => $shortCode,
            'CallBackURL' => configStore()->get('mpesa.mpesa_online.callback'),
            'TransactionType' => configStore()->get('mpesa.mpesa_online.default_transaction_type'),
            'Password' => $password,
            'PartyB' => $shortCode,
            'Timestamp' => $time,
            'PassKey' => $passkey,
        ];
        // This gives precedence to params coming from user allowing them to override config params
        $body = array_merge($configParams, $userParams);
        if (empty($body['PartyA']) && !empty($body['PhoneNumber'])) {
            $body['PartyA'] = $body['PhoneNumber'];
        }

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
