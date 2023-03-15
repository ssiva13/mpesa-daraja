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
    /*
    * Register Validation and confirmation Urls for
    */
    public function submitRequest(array $params = [], string $app = 'default')
    {

        $initiator = configStore()->get('mpesa.c2b.initiator_name');
        $confirmationUrl = configStore()->get('mpesa.c2b.confirmation_url');
        $validationUrl = configStore()->get('mpesa.c2b.validation_url');
        $responseType = configStore()->get('mpesa.c2b.response_type');
        $initiatorPass = configStore()->get('mpesa.c2b.security_credential');
        $shortCode = configStore()->get('mpesa.c2b.short_code');
        $timeoutCallback = configStore()->get('mpesa.c2b.timeout_url');
        $resultCallback = configStore()->get('mpesa.c2b.result_url');
        // security cert
        $securityCert = configStore()->get('mpesa.c2b.security_cert');

        $body = [
            'InitiatorName' => $initiator,
            'ShortCode' => $shortCode,
            'ResponseType' => $responseType,
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
            'SecurityCredential' => computeSecurityCredential($initiatorPass, $securityCert),
            'QueueTimeOutURL' => $timeoutCallback,
            'ResultURL' => $resultCallback,
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
