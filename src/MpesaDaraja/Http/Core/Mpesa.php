<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Core;

use Ssiva\MpesaDaraja\Http\Auth\Authenticator;
use Ssiva\MpesaDaraja\Http\CoreClient;

trait Mpesa
{
    protected string $endpoint;
    protected CoreClient $coreClient;
    
    protected array $validationRules = [];
    
    public function setCoreClient(CoreClient $coreClient)
    {
        $this->coreClient = $coreClient;
        // $this->coreClient->setValidationRules($this->validationRules);
    }
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ErrorException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function authenticate(){
        $stk = new Authenticator();
        return $stk->authenticate();
    }
    
    
    
}