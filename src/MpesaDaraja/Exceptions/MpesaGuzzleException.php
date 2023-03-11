<?php
/**
 * Date 11/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Exceptions;

use Exception;

class MpesaGuzzleException extends Exception
{
    /**
     * @throws \Exception
     */
    public function generateException($response)
    {
        $responseCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody()->getContents(), true);

        throw new Exception(json_encode($responseBody), $responseCode);
    }
    
}
