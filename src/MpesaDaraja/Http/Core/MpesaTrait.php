<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Core;

use Ssiva\MpesaDaraja\Http\Account\Balance;
use Ssiva\MpesaDaraja\Http\Account\Reversal;
use Ssiva\MpesaDaraja\Http\Account\TransactionStatus;
use Ssiva\MpesaDaraja\Http\Auth\Authenticator;
use Ssiva\MpesaDaraja\Http\B2B\DispatchPayment as PayB2B;
use Ssiva\MpesaDaraja\Http\B2C\DispatchPayment as PayB2C;
use Ssiva\MpesaDaraja\Http\CoreClient;
use Ssiva\MpesaDaraja\Http\MpesaOnline\STKPush;
use Ssiva\MpesaDaraja\Http\MpesaOnline\STKStatusQuery;

trait MpesaTrait
{
    protected CoreClient $coreClient;
    protected array $validationRules = [];
    
    /**
     * @param array $params
     * @param string $app
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function authenticate(array $params = [], string $app = 'default'): ?string
    {
        $auth = new Authenticator($this->coreClient);
        return $auth->authenticate();
    }
    
    /**
     * @param array $params
     * @param string $app
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function stkPush(array $params = [], string $app = 'default')
    {
        $stk = new STKPush($this->coreClient);
        return $stk->submitRequest($params);
    }
    
    /**
     * @param array $params
     * @param string $app
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function stkPushQuery(array $params = [], string $app = 'default')
    {
        $stk = new STKStatusQuery($this->coreClient);
        return $stk->submitRequest($params);
    }
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function accountBalance(array $params = [], string $app = 'default')
    {
        $mpesa = new Balance($this->coreClient);
        return $mpesa->submitRequest($params);
    }
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function reversal(array $params = [], string $app = 'default')
    {
        $mpesa = new Reversal($this->coreClient);
        return $mpesa->submitRequest($params);
    }
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function transactionStatus(array $params = [], string $app = 'default')
    {
        $mpesa = new TransactionStatus($this->coreClient);
        return $mpesa->submitRequest($params);
    }
    
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function b2cPayment(array $params = [], string $app = 'default')
    {
        $mpesa = new PayB2C($this->coreClient);
        return $mpesa->submitRequest($params);
    }
    
     /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function b2bPayment(array $params = [], string $app = 'default')
    {
        $mpesa = new PayB2B($this->coreClient);
        return $mpesa->submitRequest($params);
    }
    
}
