<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Core;

use Ssiva\MpesaDaraja\Http\Auth\Authenticator;
use Ssiva\MpesaDaraja\Http\CoreClient;

trait MpesaTrait
{
    protected CoreClient $coreClient;
    protected array $validationRules = [];
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ErrorException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function authenticate($params = [], $app='default'): ?string
    {
        $stk = new Authenticator($this->coreClient);
        return $stk->authenticate();
    }
    
    
    
}