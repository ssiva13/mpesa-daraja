<?php
/**
 * Date 01/03/2023
 *
 * Query the status of a Lipa na M-Pesa Online Payment
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\MpesaOnline;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;
use Ssiva\MpesaDaraja\Http\CoreClient;

class STKStatusQuery extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/stkpushquery/v1/query';

    protected array $validationRules = [
        'Password' => 'required|string',
        'PassKey' => 'required|string',
        'Timestamp' => 'required|datetime',
        'BusinessShortCode' => 'required|numeric|min:5',
        'CheckoutRequestID' => 'required',
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
            'Password' => $password,
            'Timestamp' => $time,
            'PassKey' => $passkey,
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
