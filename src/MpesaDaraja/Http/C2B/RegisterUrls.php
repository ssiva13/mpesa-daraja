<?php

/**
 * Date 15/03/2023
 *
 * @author   kelvin mukotso <kelvinmukotso@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\C2B;

use Ssiva\MpesaDaraja\Http\AbstractDarajaQuery;

class RegisterUrls extends AbstractDarajaQuery
{
    protected string $endpoint = 'mpesa/c2b/v1/registerurl';
    
    protected array $validationRules = [
        'ConfirmationURL' => 'required|url',
        'ValidationURL' => 'required|url',
        'ShortCode' => 'required|numeric|min:5',
        'ResponseType' => 'required|string|max:15|exists_in:ResponseType_c2b',
    ];
    
    /*
    * Register Validation and confirmation Urls for
    */
    public function submitRequest(array $params = [], string $app = 'default')
    {

        $confirmationUrl = configStore()->get('mpesa.c2b.confirmation_url');
        $validationUrl = configStore()->get('mpesa.c2b.validation_url');
        $responseType = configStore()->get('mpesa.c2b.response_type');
        $shortCode = configStore()->get('mpesa.c2b.short_code');

        $body = [
            'ShortCode' => $shortCode,
            'ResponseType' => $responseType,
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
        ];

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
