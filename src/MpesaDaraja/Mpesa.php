<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */


namespace Ssiva\MpesaDaraja;

use Ssiva\MpesaDaraja\Http\Auth\Authenticator;
use Ssiva\MpesaDaraja\Http\Core\Cache;
use Ssiva\MpesaDaraja\Http\Core\Config;
use Ssiva\MpesaDaraja\Http\Core\MpesaTrait;
use Ssiva\MpesaDaraja\Http\CoreClient;

class Mpesa
{
    use MpesaTrait;
    
    /**
     * @var \Ssiva\MpesaDaraja\Http\CoreClient
     */
    private CoreClient $darajaClient;
    
    /**
     * Mpesa constructor.
     *
     */
    public function __construct($myconfig = []){
        $config = new Config($myconfig);
        $cache = new Cache($config);
        $auth = new Authenticator();

        $this->darajaClient = new CoreClient($config, $cache,$auth);
    }
    
}