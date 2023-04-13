<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Exceptions;

use Exception;

class AuthException extends Exception
{
    
    /**
     * @throws \Ssiva\MpesaDaraja\Exceptions\ErrorException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function generateException()
    {
        $message = $this->getMessage();
        switch (\strtolower($message)) {
            case 'bad request: invalid credentials':
                throw new ConfigurationException('Invalid consumer key and secret combination');
            default:
                throw new ErrorException($message);
        }
    }
    
}