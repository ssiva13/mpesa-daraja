<?php
/**
 * Date 12/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http;
interface DarajaQueryInterface
{
    /**
     * Auth constructor.
     * @param CoreClient $coreClient
     */
    public function __construct(CoreClient $coreClient);
    
    /**
     * Get the bearer token.
     *
     * @param $app
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     */
    public function bearer($app): string;
    
    /**
     * @param array $params
     * @param string $app
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Ssiva\MpesaDaraja\Exceptions\ConfigurationException
     * @throws \Exception
     */
    public function submitRequest(array $params = [], string $app = 'default');
}
